<?php
/**
 * Xoositemap module
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
 * @package         Xoositemap
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class XootagsXoositemapPlugin extends Xoops_Module_Plugin_Abstract implements XoositemapPluginInterface
{
    public function Xoositemap($subcategories)
    {
        $tags_module = Xootags::getInstance();
        $tags_config = $tags_module->LoadConfig();
        $tags_tags_handler = $tags_module->TagsHandler();

        $criteria = new CriteriaCompo();
        $criteria->setSort('tag_count');
        $criteria->setOrder('DESC');
        $criteria->setLimit($tags_config['xootags_limit_tag_main']);
        $tags = $tags_tags_handler->getObjects($criteria, false, false);

        $sitemap = array();
        foreach ($tags as $k => $tag) {
            $sitemap[$k]['id']    = $k;
            $sitemap[$k]['title'] = $tag['tag_term'];
            $sitemap[$k]['url']   = XOOPS_URL . '/modules/xootags/tag.php?tag_id=' . $tag['tag_id'];
        }
        return $sitemap;
    }
}