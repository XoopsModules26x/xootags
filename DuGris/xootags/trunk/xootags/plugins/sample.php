<?php
/**
 * Xooghost module
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
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */


/**
 * define functions to build info of tagged items
 *
 * @param    array    ids tagged items
 *
 * @return   array    fields items : id
 *                                   title
 *                                   link
 *                                   time
 *                                   uid
 */
function XooTags_<moduledirname>( $items )
{
    // sql where clause
    $sql .= 'WHERE item_id IN (' . explode(',', $items) . ')';

    // xoops criteria
    $criteria = new CriteriaCompo();
    $criteria->add( new Criteria('item_id', '(' . implode(', ', $items) . ')', 'IN') ) ;


    $k = $item_time;
    $ret[$k]['item_id']    = $item_id;
    $ret[$k]['item_title'] = $item_title;
    $ret[$k]['item_link']  = $item_link; // without http
    $ret[$k]['item_time']  = $item_time;
    $ret[$k]['item_uid']   = $item_uid;

    return $ret;
}
?>