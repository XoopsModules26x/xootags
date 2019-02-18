<?php
/**
 * Xootags module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xootags
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */
use Xoops\Core\Request;

include __DIR__ . '/header.php';

$tag_id = Request::getInt('tag_id', 0); //$system->cleanVars($_REQUEST, 'tag_id', 0, 'int');
$module_id = Request::getInt('module_id', 0); //$system->cleanVars($_REQUEST, 'module_id', 0, 'int');
$start = Request::getInt('start', 0); //$system->cleanVars($_REQUEST, 'start', 0, 'int');

if (0 == $tag_id) {
    $xoops->redirect('index.php', 5);
}
if (0 != $module_id) {
    $modules = $linkHandler->getByModule($module_id, $tag_id);
} else {
    $modules = $linkHandler->getByModule(0, $tag_id);
}

foreach ($modules as $k => $module) {
    $mid = $module['tag_modid'];
    $items[$mid]['item_id'][] = $module['tag_itemid'];
}

$tags = [];
$i = 0;
foreach ($items as $mid => $module) {
    $moduleObj = $moduleHandler->get($mid);

    $plugin = \Xoops\Module\Plugin::getPlugin($moduleObj->getVar('dirname'), 'xootags');
    $results = $plugin->Xootags($module['item_id'], $start, $limit = 0);

    if (is_array($results) && count($results) > 0) {
        foreach ($results as $data) {
            $tags[$i]['modules'][$mid]['mid'] = $mid;
            $tags[$i]['modules'][$mid]['name'] = $moduleObj->getVar('name');
            $tags[$i]['modules'][$mid]['dirname'] = $moduleObj->getVar('dirname');
            $tags[$i]['modules'][$mid]['image'] = $xoops->url('/modules/' . $moduleObj->getVar('dirname') . '/assets/icons/logo_small.png');
            $tags[$i]['tag_id'] = $tag_id;
            $tags[$i]['link'] = $xoops->url('/modules/' . $moduleObj->getVar('dirname') . '/' . $data['link']);
            $tags[$i]['title'] = $data['title'];
            $tags[$i]['date'] = \XoopsLocale::formatTimestamp($data['time'], 's');
            $tags[$i]['uid'] = $data['uid'];
            $tags[$i]['uid_name'] = \XoopsUser::getUnameFromId($data['uid'], true);
            $tags[$i]['tags'] = $tagsHandler->getbyItem($data['itemid'], $mid);
            $tags[$i]['content'] = $data['content'];
            $dates[$i] = ['time' => $data['time']];

            // metas
            $keywords[] = $data['title'];

            ++$i;
        }
    }
}

array_multisort($dates, SORT_DESC, $tags);

$subtitle = [];
if (0 != $tag_id) {
    $subtitle[] = sprintf(_XOO_TAGS_TERM, $tagsHandler->get($tag_id)->getVar('tag_term'));
}
if (0 != $module_id) {
    $subtitle[] = sprintf(_XOO_TAGS_MODULE, $moduleHandler->get($module_id)->getVar('name'));
}

$xoops->tpl()->assign('tags', array_slice($tags, $start, $tagsConfig['xootags_limit_tag_tag']));
$xoops->tpl()->assign('subtitle', $subtitle);

// Page navigation
$paginate = new \XoopsModules\Xootags\XooPaginate(count($tags), $tagsConfig['xootags_limit_tag_tag'], $start, 'start', 'tag_id=' . $tag_id);

// Metas
$utilities = new \XoopsModules\Xootags\Utility();
$xoops->theme()->addMeta($type = 'meta', 'description', $utilities->getMetaDescription($keywords));
$xoops->theme()->addMeta($type = 'meta', 'keywords', $utilities->getMetaKeywords($keywords));
$xoops->tpl()->assign('xoops_pagetitle', $tagsHandler->get($tag_id)->getVar('tag_term') . ' - ' . $xoops->module->getVar('name'));

include __DIR__ . '/footer.php';
