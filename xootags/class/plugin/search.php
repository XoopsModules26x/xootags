<?php
/**
 * xootags module
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
 * @package         xootags
 * @since           2.6.0
 * @author          Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

/**
 * Class XootagsSearchPlugin
 */
class XootagsSearchPlugin extends Xoops\Module\Plugin\PluginAbstract implements SearchPluginInterface
{
    /**
     * @param $queries
     * @param $andor
     * @param $limit
     * @param $start
     * @param $uid
     *
     * @return array
     */
    public function search($queries, $andor, $limit, $start, $uid)
    {
        $searchstring = '';
        $ret          = array();

        $criteria = new CriteriaCompo();

        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $criteria->setSort('tag_count');
        $criteria->setOrder('ASC');

        $criteria->add(new Criteria('tag_status', 1));

        if (is_array($queries) && $count = count($queries)) {
            foreach ($queries as $k => $v) {
                $criteria_content = new CriteriaCompo();
                $criteria_content->add(new Criteria('tag_term', '%' . $v . '%', 'LIKE'), 'OR');
                $criteria->add($criteria_content, $andor);
            }
        }

        $tagsModule       = Xootags::getInstance();
        $tagsTagsHandler = $tagsModule->TagsHandler();

        $tags = $tagsTagsHandler->getObjects($criteria, true, false);

        $k = 0;
        foreach ($tags as $tag) {
            $ret[$k]['image'] = 'assets/icons/logo_small.png';
            $ret[$k]['link']  = 'tag.php?tag_id=' . $tag['tag_id'];
            $ret[$k]['title'] = $tag['tag_term'];
            ++$k;
        }

        return $ret;
    }
}
