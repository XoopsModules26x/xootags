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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

function xootags_search($queryarray, $andor, $limit, $offset, $userid)
{
    $xoops = Xoops::getInstance();
    $searchstring = '';
    $ret = array();

    $criteria = new CriteriaCompo();

    $criteria->setLimit($limit);
    $criteria->setStart($offset);
    $criteria->setSort('tag_count');
    $criteria->setOrder('ASC');

    $criteria->add( new Criteria('tag_status', 1) ) ;

    if ( is_array($queryarray) && $count = count($queryarray) ) {
        foreach ($queryarray as $k => $v) {
            $criteria_content = new CriteriaCompo();
            $criteria_content->add( new Criteria('tag_term', '%' . $v . '%', 'LIKE'), 'OR' ) ;
            $criteria->add( $criteria_content, $andor);
        }
    }

    $xootags_handler = $xoops->getModuleHandler('xootags_tags', 'xootags');
    $tags = $xootags_handler->getObjects($criteria, false, false);

    foreach ( $tags as $k => $tag ) {
        $ret[$k]['image']    = 'icons/logo_small.png';
        $ret[$k]['link']     = 'tag.php?tag_id=' . $tag['tag_id'];
        $ret[$k]['title']    = $tag['tag_term'];
    }
    return $ret;
}
?>