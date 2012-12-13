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

function XooSitemap_xootags( $subcategories = true)
{
    $xoops = Xoops::getInstance();

    XoopsLoad::load('xoopreferences', 'xootags');
    $Xootags_config = XooTagsPreferences::getInstance()->getConfig();

    $xootags_tags_handler = $xoops->getModuleHandler('xootags_tags', 'xootags');
    $criteria = new CriteriaCompo();
    $criteria->setSort('tag_count');
    $criteria->setOrder('DESC');
    $criteria->setLimit($Xootags_config['xootags_limit_tag_main']);
    $tags = $xootags_tags_handler->getObjects($criteria, false, false);

    $sitemap = array();
    foreach ($tags as $k => $tag) {        $sitemap[$k]['id']    = $k;
        $sitemap[$k]['title'] = $tag['tag_term'];
        $sitemap[$k]['url']   = XOOPS_URL . '/modules/xootags/tag.php?tag_id=' . $tag['tag_id'];
    }
    return $sitemap;
}
?>