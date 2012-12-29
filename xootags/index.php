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

$start = $system->CleanVars($_REQUEST, 'start', 0, 'int');

$criteria = new CriteriaCompo();
$criteria->setSort('tag_count');
$criteria->setOrder('DESC');
$criteria->setStart( $start );
$criteria->setLimit($tags_config['xootags_limit_tag_main']);

$tags = $tags_tags_handler->getObjects($criteria, false, false);
$tags_count = $tags_tags_handler->getCount($criteria);
$tags_max = 1;
$tags_min = 1;
foreach( $tags as $k => $tag) {    $tags_max = ($tag['tag_count'] > $tags_max) ? $tag['tag_count'] : $tags_max;    $tags_min = ($tag['tag_count'] < $tags_min) ? $tag['tag_count'] : $tags_min;
    $bytags = $tags_link_handler->getByTag( $tag['tag_id'] );
    foreach ($bytags as $j => $mod ) {        $mid = $mod['tag_modid'];        $module = $module_Handler->get( $mid );
        $tags[$k]['modules'][$mid]['mid'] = $mid;
        $tags[$k]['modules'][$mid]['name'] = $module->getVar('name');
        $tags[$k]['modules'][$mid]['dirname'] = $module->getVar('dirname');
        $tags[$k]['modules'][$mid]['image'] = $xoops->url('/modules/' . $module->getVar('dirname') . '/icons/logo_small.png');
    }
}

// font size
$font_max = $tags_config['xootags_font_max'];
$font_min = $tags_config['xootags_font_min'];
$tags_interval = $tags_max - $tags_min;
$font_ratio = ($tags_interval) ? ($font_max - $font_min) / $tags_interval : 1;
foreach( $tags as $k => $tag) {    $tags[$k]['font'] = empty($tags_interval) ? 100 : floor( ($tag['tag_count'] - $tags_min) * $font_ratio ) + $font_min;
    $tags[$k]['size'] = (floor( ($tag['tag_count'] - $tags_min) * $font_ratio ) + $font_min) / 10;
}

// Page navigation
$paginate = new Xoopaginate($tags_count, $tags_config['xootags_limit_tag_main'], $start, 'start', '');

$xoops->tpl()->assign('tags', $tags);
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>