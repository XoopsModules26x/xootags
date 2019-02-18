<?php

namespace XoopsModules\Xootags;

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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         xootags
 * @since           2.6.0
 * @author          Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id: xootagslink.php 1440 2013-01-17 14:15:30Z DuGris $
 */
use Xoops\Core\Database\Connection;

/**
 * Class LinkHandler
 */
class LinkHandler extends \XoopsPersistableObjectHandler
{
    public $table_link;

    public $field_link;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'xootags_link', 'Link', 'tl_id', 'tag_itemid');
        $this->className = 'Link';
    }

    /**
     * @param $criteria
     *
     * @return array
     */
    public function getTags($criteria)
    {
        $this->className = 'XootagsGetByModule';
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
        $this->className = 'XootagsGetByModule';
        $this->table_link = $this->db2->prefix('xootags_tags');
        $this->field_link = 'tag_id';
        $criteria = new \CriteriaCompo();
        if (0 != $module_id) {
            $criteria->add(new \Criteria('o.tag_modid', $module_id));
        }
        if (0 != $tag_id) {
            $criteria->add(new \Criteria('o.tag_id', $tag_id));
        } else {
            $criteria->setGroupBy('o.tag_id');
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
        $this->className = 'XootagsGetByModule';
        $this->table_link = $this->db2->prefix('xootags_tags');
        $this->field_link = 'tag_id';

        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('l.tag_id', $itemid));
        $criteria->setGroupBy('o.tag_modid');

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
        $this->className = 'XootagsGetByItem';
        $this->tableLink = $this->db2->prefix('xootags_tags');
        $this->fieldLink = 'tag_id';

        $itemid = (int)($itemid);

        $xoops = \Xoops::getInstance();
        if (null === $modid && (is_object($xoops->module) && 'xootags' !== $xoops->module->dirname())) {
            $modid = $xoops->module->getVar('mid');
        }

        if (null !== $modid) {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('o.tag_itemid', $itemid));
            $criteria->add(new \Criteria('o.tag_modid', $modid));

            $criteria->setSort('l.tag_count');
            $criteria->setOrder('desc');

            $tags = $this->getByLink($criteria, null, false, false);
            if (!$onlyTags) {
                return $tags;
            }

            $tmp = [];
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
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('tag_id', $tag_id));

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
        $xoops = \Xoops::getInstance();
        if (null === $modid && 'xootags' !== $xoops->module->dirname()) {
            $modid = $xoops->module->getVar('mid');
        }
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN'));
        $criteria->add(new \Criteria('tag_itemid', $itemid));
        $criteria->add(new \Criteria('tag_modid', $modid));

        return parent::deleteAll($criteria);
    }
}
