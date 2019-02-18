<?php

namespace XoopsModules\Xootags\Plugin;

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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xoositemap
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)

 */

/**
 * Class XoositemapPlugin
 */
class XoositemapPlugin extends \Xoops\Module\Plugin\PluginAbstract implements \XoositemapPluginInterface
{
    /**
     * @param $subcategories
     *
     * @return array
     */
    public function xooSitemap($subcategories)
    {
        $helper = \XoopsModules\Xootags\Helper::getInstance();
        $tagsConfig = $helper->loadConfig();
        $tagsHandler = $helper->getHandler('Tags');
        $linkHandler = $helper->getHandler('Link');

        $criteria = new \CriteriaCompo();

        $criteria->setSort('tag_time');
        $criteria->setOrder('DESC');
        $criteria->setGroupBy('l.tag_id');
        $criteria->setLimit($tagsConfig['xootags_limit_tag_main']);
        $tags = $linkHandler->getTags($criteria);

        $sitemap = [];
        foreach ($tags as $k => $tag) {
            $sitemap[$k]['id']    = $k;
            $sitemap[$k]['title'] = $tag['tag_term'];
            $sitemap[$k]['url']   = \XoopsBaseConfig::get('url')  . '/modules/xootags/tag.php?tag_id=' . $tag['tag_id'];
            $sitemap[$k]['time']  = $tag['tag_time'];
        }

        return $sitemap;
    }

    /**
     * @param $subcategories
     *
     * @return array
     */
    public function xoositemap_xml($subcategories)
    {
        $helper = \XoopsModules\Xootags\Helper::getInstance();
        $tagsConfig = $helper->loadConfig();
        $tagsHandler = $helper->getHandler('Tags');
        $linkHandler = $helper->getHandler('Link');

        $sitemap = [];
        $time    = 0;

        $criteria = new \CriteriaCompo();
        $criteria->setSort('tag_time');
        $criteria->setOrder('DESC');
        $criteria->setGroupBy('l.tag_id');
        $criteria->setLimit($tagsConfig['xootags_limit_tag_main']);

        $tags = $linkHandler->getTags($criteria);
        foreach ($tags as $k => $tag) {
            $sitemap[$k]['url']  = \XoopsBaseConfig::get('url')  . '/modules/xootags/tag.php?tag_id=' . $tag['tag_id'];
            $sitemap[$k]['time'] = $tag['tag_time'];
            if ($time < $tag['tag_time']) {
                $time = $tag['tag_time'];
            }
        }

        return ['dirname' => \XoopsModules\Xootags\Helper::getInstance()->getModule()->getVar('dirname'), 'time' => $time, 'items' => $sitemap];
    }
}
