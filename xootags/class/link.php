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
 * @version         $Id: xootagslink.php 1440 2013-01-17 14:15:30Z DuGris $
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * Class XootagsLink
 */
class XootagsLink extends XoopsObject
{
    // constructor
    /**
     *
     */
    public function __construct()
    {
        $this->initVar('tl_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tag_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_modid', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_itemid', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_time', XOBJ_DTYPE_INT, 0);
    }

    /**
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     *
     * @return array
     */
    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        return parent::getValues();
    }
}

/**
 * Class XootagsGetByModule
 */
class XootagsGetByModule extends XoopsObject
{
    // constructor
    /**
     *
     */
    public function __construct()
    {
        $this->initVar('tl_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tag_modid', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_itemid', XOBJ_DTYPE_INT, 0);

        $this->initVar('tag_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tag_term', XOBJ_DTYPE_TXTBOX, '', true);
        $this->initVar('tag_status', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_count', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_time', XOBJ_DTYPE_INT, 0);
    }


    /**
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     *
     * @return array
     */
    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        $ret                = parent::getValues();
        $ret['tag_term_js'] = $this->js_escape($this->getVar('tag_term'));

        return $ret;
    }

    /**
     * @param $str
     *
     * @return mixed
     */
    public function js_escape($str)
    {
        $search  = array("&#039;", "&amp;", "&#176;", "&#128;");
        $replace = array("\u0027", "\u0026", "\u00b0", "\u20AC");

        return str_replace($search, $replace, $str);
    }
}

/**
 * Class XootagsGetByItem
 */
class XootagsGetByItem extends XoopsObject
{
    // constructor
    /**
     *
     */
    public function __construct()
    {
        $this->initVar('tag_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tag_term', XOBJ_DTYPE_TXTBOX, '', true);
        $this->initVar('tag_time', XOBJ_DTYPE_INT, 0);
    }

    /**
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     *
     * @return array
     */
    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        $ret                = parent::getValues();
        $ret['tag_term_js'] = $this->js_escape($this->getVar('tag_term'));

        return $ret;
    }

    /**
     * @param $str
     *
     * @return mixed
     */
    public function js_escape($str)
    {
        $search  = array("&#039;", "&amp;", "&#176;", "&#128;");
        $replace = array("\u0027", "\u0026", "\u00b0", "\u20AC");

        return str_replace($search, $replace, $str);
    }
}

/**
 * Class XootagsLinkHandler
 */
class XootagsLinkHandler extends XoopsPersistableObjectHandler
{
    public $table_link;

    public $field_link;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'xootags_link', 'XootagsLink', 'tl_id', 'tag_itemid');
        $this->className = 'XootagsLink';
    }

    /**
     * @param $criteria
     *
     * @return array
     */
    public function getTags($criteria)
    {
        $this->className  = 'XootagsGetByModule';
        $this->table_link = $this->db2->prefix('xootags_tags');
//        $this->table_link = $xoops->db->prefix('xootags_tags');
        $this->field_link = 'tag_id';

        return parent::getByLink($criteria, null, false, false);
    }

    /**
     * @param     $module_id
     * @param int $tag_id
     *
     * @return array
     */
    public function getByModule($module_id, $tag_id = 0)
    {
        $this->className  = 'XootagsGetByModule';
        $this->table_link = $this->db2->prefix('xootags_tags');
        $this->field_link = 'tag_id';
        $criteria         = new CriteriaCompo();
        if ($module_id != 0) {
            $criteria->add(new Criteria('o.tag_modid', $module_id));
        }
        if ($tag_id != 0) {
            $criteria->add(new Criteria('o.tag_id', $tag_id));
        } else {
            $criteria->setGroupby('o.tag_id');
        }

        return parent::getByLink($criteria, null, false, false);
    }

    /**
     * @param $itemid
     *
     * @return array
     */
    public function getByTag($itemid)
    {
        $this->className  = 'XootagsGetByModule';
        $this->table_link = $this->db2->prefix('xootags_tags');
        $this->field_link = 'tag_id';

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('l.tag_id', $itemid));
        $criteria->setGroupby('o.tag_modid');

        return parent::getByLink($criteria, null, false, false);
    }

    /**
     * @param      $itemid
     * @param null $modid
     * @param bool $onlyTags
     *
     * @return array
     */
    public function getByItem($itemid, $modid = null, $onlyTags = false)
    {
        $this->className  = 'XootagsGetByItem';
        $this->tableLink = $this->db2->prefix('xootags_tags');
        $this->fieldLink = 'tag_id';

        $itemid = (int)($itemid);

        $xoops = Xoops::getInstance();
        if (is_null($modid) && (is_object($xoops->module) && $xoops->module->dirname() !== 'xootags')) {
            $modid = $xoops->module->getVar('mid');
        }

        if (!is_null($modid)) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('o.tag_itemid', $itemid));
            $criteria->add(new Criteria('o.tag_modid', $modid));

            $criteria->setSort('l.tag_count');
            $criteria->setOrder('desc');

            $tags = $this::getByLink($criteria, null, false, false);
            if (!$onlyTags) {
                return $tags;
            }

            $tmp = array();
            foreach ($tags as $k => $v) {
                $tmp[] = $v['tag_term'];
            }

            return $tmp;
        }
        return null;
    }

    /**
     * @param $tag_id
     *
     * @return bool
     */
    public function deleteByItem($tag_id)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('tag_id', $tag_id));

        return parent::deleteAll($criteria);
    }

    /**
     * @param      $tag_ids
     * @param      $itemid
     * @param null $modid
     *
     * @return bool
     */
    public function deleteByIds($tag_ids, $itemid, $modid = null)
    {
        $xoops = Xoops::getInstance();
        if (is_null($modid) && $xoops->module->dirname() !== 'xootags') {
            $modid = $xoops->module->getVar('mid');
        }
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN'));
        $criteria->add(new Criteria('tag_itemid', $itemid));
        $criteria->add(new Criteria('tag_modid', $modid));

        return parent::deleteAll($criteria);
    }
}
