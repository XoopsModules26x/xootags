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

class Xootags_tags extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar('tag_id',            XOBJ_DTYPE_INT,     null, false);
        $this->initVar('tag_term',          XOBJ_DTYPE_TXTBOX,     '', true);
        $this->initVar('tag_status',        XOBJ_DTYPE_INT,     0);
        $this->initVar('tag_count',         XOBJ_DTYPE_INT,     0);
	}

    private function Xootags_tags()
    {
        $this->__construct();
    }

    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        return $ret;
}

class xootagsxootags_tagsHandler extends XoopsPersistableObjectHandler
{

    public function __construct(&$db)
    {
        parent::__construct($db, 'xootags_tags', 'Xootags_tags', 'tag_id', 'tag_term');
    }

    private function isActive()
    {
        $xoops = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();
        $module = $module_handler->getByDirname('xooTags');
        return ($module && $module->getVar('isactive')) ? true : false;
    }

    public function SetOnline( $tag_id )
    {
        if ($tag_id != 0){
            $tag = $this->get( $tag_id );
            if ( $tag->getVar('tag_status') == 1 ) {
                $tag->setVar('tag_status', 0);
            } else {
                $tag->setVar('tag_status', 1);
            }
            $this->insert( $tag );
            return true;
        }
        return false;
    }

    public function getbyItem( $itemid, $modid = null, $onlytags=false)
    {
        $xoops = xoops::getinstance();
        $xoops->loadLanguage('main', 'xootags');
        if ( $this->isActive() ) {
            $xoops->theme()->addStylesheet('modules/xootags/css/module.css');

            $xootags_link_handler = $xoops->getModuleHandler('xootags_link', 'xootags');
            return $xootags_link_handler->getbyItem( $itemid, $modid, $onlytags);
        } else {
            $xoops->logger->handleError( 2 , '<strong><span class="red">' . _XOO_TAGS_TAGS_ERROR . '</span></strong>', $xoops->getenv('PHP_SELF'), 'TagsForm(...)' );
            return false;
        }
    }

    public function updateByItem( $name='tags', $itemid=0 )
    {
        $xoops->loadLanguage('main', 'xootags');

        $itemid = intval($itemid);
        $mid = $xoops->module->getVar('mid');

        if ( empty($itemid) || empty($mid) || is_null($name) ) {

        XoopsLoad::load('xoopreferences', 'xootags');
        $Tags_config = XooTagsPreferences::getInstance()->getConfig();

        if ( array_key_exists($name, $_POST) === false ) {
            $tags = explode(',', $_POST[$name]);
            $tags = array_filter(array_map('trim', $tags));
        }

        $tags_existing      = $this->getByItem($itemid, $mid, true);
        $tags_delete        = array_diff(array_values($tags_existing), $tags);
        $tags_add           = array_diff($tags, array_values($tags_existing));

        if (!empty($tags_delete)) {
            $tag_ids = $this->getIds(new Criteria('tag_term', '(' . implode(', ', $tags_delete) . ')', 'IN'));
            $xootags_link_handler = $xoops->getModuleHandler('xootags_link', 'xootags');
            if ( !$xootags_link_handler->DeleteByIds( $tag_ids, $itemid ) ) {
            unset($xootags_link_handler);

            $criteria = new CriteriaCompo();
            $criteria->add( new Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN') ) ;
            $criteria->add( new Criteria('tag_count', 2, '<') ) ;
            if ( !parent::deleteAll($criteria) ) {

/*
            $criteria = new CriteriaCompo();
            $criteria->add( new Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN') ) ;
            if ( !parent::updateAll('tag_count', 'tag_count `1 1' , $criteria) ) {
            }
*/
            $sql =  'UPDATE ' . $this->table .
                    ' SET tag_count = tag_count - 1' .
                    ' WHERE ' .
                    $this->keyName . ' IN (' . implode(', ', $tag_ids) . ')';
            if ( ($result = $this->db->queryF($sql)) == false) {
                //xoops_error($this->db->error());
            }
        }

        if (!empty($tags_add)) {
                    $tag_id = $tagObj->getVar('tag_id');
                } else {
                    $tagObj->setVar('tag_count', 1);
                    $tagObj->setVar('tag_status', 1);
                    $tag_id = $this->insert($tagObj);
                }

                $xootags_link_handler = $xoops->getModuleHandler('xootags_link', 'xootags');
                $xootags_link_handler->className = 'Xootags_link';
                $criteria = new CriteriaCompo();
                $criteria->add( new Criteria('tag_id', $tag_id) ) ;
                $criteria->add( new Criteria('tag_modid', $mid) ) ;
                $criteria->add( new Criteria('tag_itemid', $itemid) ) ;
                if ( !$tagLink = $xootags_link_handler->getObjects( $criteria ) ) {
                    $tagLink->setVar('tag_id', $tag_id);
                    $tagLink->setVar('tag_modid', $mid);
                    $tagLink->setVar('tag_itemid', $itemid);
                    $tagLink->setVar('tag_time', time());
                    if ( !$xootags_link_handler->insert($tagLink) ) {
                }

                if ($tag_update) {
                    $this->insert($tagObj);
                }
            }
        }
    }

    function deleteByItem($itemid=0)
    {
}
?>