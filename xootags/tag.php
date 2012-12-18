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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xootags
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

$tag_id = $system->CleanVars($_REQUEST, 'tag_id', 0, 'int');
$module_id = $system->CleanVars($_REQUEST, 'module_id', 0, 'int');

if ($tag_id == 0) {    $xoops->redirect('index.php', 5);}
if ( $module_id != 0 ) {    $modules = $xootags_link_handler->getByModule( $module_id, $tag_id);
} else {    $modules = $xootags_link_handler->getByModule( 0, $tag_id);
}

foreach ($modules as $k => $module) {
    $mid = $module['tag_modid'];
    $items[$mid]['item_id'][]   = $module['tag_itemid'];
}

$tags = array();
foreach ($items as $mid => $module) {    $moduleObj = $module_Handler->get( $mid ) ;

    $plugin = Xoops_Plugin::getPlugin($moduleObj->getVar('dirname'), 'xootags');
    if (is_object($plugin)) {
        $results = $plugin->Xootags($module['item_id']);

        if ( count($results) > 0 ) {            foreach ($results as $k => $data) {                $k = $k + '-' + $mid;
                $tags[$k]['modules'][$mid]['mid'] = $mid;
                $tags[$k]['modules'][$mid]['name'] = $moduleObj->getVar('name');
                $tags[$k]['modules'][$mid]['dirname'] = $moduleObj->getVar('dirname');
                $tags[$k]['modules'][$mid]['image'] = $xoops->url('/modules/' . $moduleObj->getVar('dirname') . '/icons/logo_small.png');

                $tags[$k]['tag_id']   = $tag_id;
                $tags[$k]['link']     = $xoops->url('/modules/' . $moduleObj->getVar('dirname') . '/' . $data['link']);
                $tags[$k]['title']    = $data['title'];
                $tags[$k]['time']     = $data['time'];
                $tags[$k]['uid']      = $data['uid'];
                $tags[$k]['uid_name'] = XoopsUser::getUnameFromId($data['uid'], true);
                $tags[$k]['tags']     = $xootags_tags_handler->getbyItem($data['itemid'], $mid);
                $tags[$k]['content']  = $data['content'];
            }
        }    }
}
krsort($tags);
$xoops->tpl()->assign('tags', $tags );

$subtitle = array();
if ($tag_id != 0) {    $subtitle[] = sprintf(_XOO_TAGS_TERM, $xootags_tags_handler->get($tag_id)->getVar('tag_term') );}
if ($module_id != 0) {
    $subtitle[] = sprintf(_XOO_TAGS_MODULE, $module_Handler->get($module_id)->getVar('name') );
}

$xoops->tpl()->assign('subtitle', $subtitle );
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>