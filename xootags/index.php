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

include __DIR__ .  '/header.php';

$start = $system->cleanVars($_REQUEST, 'start', 0, 'int');

$criteria = new CriteriaCompo();
$criteria->setSort('tag_count');
$criteria->setOrder('DESC');
$criteria->setStart($start);
$criteria->setLimit($tagsConfig['xootags_limit_tag_main']);

$tags       = $tagsTagsHandler->getObjects($criteria, false, false);
$tags_count = $tagsTagsHandler->getCount($criteria);
$tags_max   = 1;
$tags_min   = 1;
foreach ($tags as $k => $tag) {
    $keywords[] = $tag['tag_term'];
    $tags_max   = ($tag['tag_count'] > $tags_max) ? $tag['tag_count'] : $tags_max;
    $tags_min   = ($tag['tag_count'] < $tags_min) ? $tag['tag_count'] : $tags_min;
    $bytags     = $tagsLinkHandler->getByTag($tag['tag_id']);
    foreach ($bytags as $j => $mod) {
        $mid                                  = $mod['tag_modid'];
        $module                               = $moduleHandler->get($mid);
        $tags[$k]['modules'][$mid]['mid']     = $mid;
        $tags[$k]['modules'][$mid]['name']    = $module->getVar('name');
        $tags[$k]['modules'][$mid]['dirname'] = $module->getVar('dirname');
        $tags[$k]['modules'][$mid]['image']   = $xoops->url('/modules/' . $module->getVar('dirname') . '/assets/icons/logo_small.png');
    }
}

// font size
$font_max      = $tagsConfig['xootags_font_max'];
$font_min      = $tagsConfig['xootags_font_min'];
$tags_interval = $tags_max - $tags_min;
$font_ratio    = ($tags_interval) ? ($font_max - $font_min) / $tags_interval : 1;
foreach ($tags as $k => $tag) {
    $tags[$k]['font'] = empty($tags_interval) ? 100 : floor(($tag['tag_count'] - $tags_min) * $font_ratio) + $font_min;
    $tags[$k]['size'] = (floor(($tag['tag_count'] - $tags_min) * $font_ratio) + $font_min) / 10;
}
$xoops->tpl()->assign('tags', $tags);

// Page navigation
$paginate = new Xoopaginate($tags_count, $tagsConfig['xootags_limit_tag_main'], $start, 'start', '');

// Metas
$utilities = new XooTagsUtilities();
$xoops->theme()->addMeta($type = 'meta', 'description', $utilities->getMetaDescription($keywords));
$xoops->theme()->addMeta($type = 'meta', 'keywords', $utilities->getMetaKeywords($keywords));

include __DIR__ .  '/footer.php';
