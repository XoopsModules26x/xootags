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

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * Class XootagsTags
 */
class XootagsTags extends XoopsObject
{
    // constructor
    /**
     *
     */
    public function __construct()
    {
        $this->initVar('tag_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tag_term', XOBJ_DTYPE_TXTBOX, '', true);
        $this->initVar('tag_status', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_count', XOBJ_DTYPE_INT, 0);
    }

    private function XootagsTags()
    {
        $this->__construct();
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
 * Class xootagsxootags_tagsHandler
 */
class xootagsxootags_tagsHandler extends XoopsPersistableObjectHandler
{

    /**
     * @param null|\Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'xootags_tags', 'XootagsTags', 'tag_id', 'tag_term');
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        $xoops          = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();
        $module         = $module_handler->getByDirname('xooTags');

        return ($module && $module->getVar('isactive')) ? true : false;
    }

    /**
     * @param $tag_id
     *
     * @return bool
     */
    public function SetOnline($tag_id)
    {
        if ($tag_id != 0) {
            $tag = $this->get($tag_id);
            if ($tag->getVar('tag_status') == 1) {
                $tag->setVar('tag_status', 0);
            } else {
                $tag->setVar('tag_status', 1);
            }
            $this->insert($tag);

            return true;
        }

        return false;
    }

    /**
     * @param      $itemid
     * @param null $modid
     * @param bool $onlyTags
     *
     * @return bool
     */
    public function getByItem($itemid, $modid = null, $onlyTags = false)
    {
        $xoops       = Xoops::getInstance();
        $tagsModule = Xootags::getInstance();
        if ($this->isActive()) {
            $xoops->theme()->addStylesheet('modules/xootags/assets/css/module.css');

            $tagsLinkHandler = $tagsModule->LinkHandler();

            return $tagsLinkHandler->getbyItem($itemid, $modid, $onlyTags);
        } else {
            $xoops->logger->handleError(2, '<strong><span class="red">' . _XOO_TAGS_TAGS_ERROR . '</span></strong>', $xoops->getEnv('PHP_SELF'), 'TagsForm(...)');

            return false;
        }
    }

    /**
     * @param string $name
     * @param int    $itemid
     *
     * @return string
     */
    public function updateByItem($name = 'tags', $itemid = 0)
    {
        $xoops       = Xoops::getInstance();
        $tagsModule = Xootags::getInstance();

        $itemid = (int)($itemid);
        $mid    = $xoops->module->getVar('mid');

        if (empty($itemid) || empty($mid) || is_null($name)) {
            return _XOO_TAGS_ADD_TAGS_ERROR;
        }

        $tagsModule = Xootags::getInstance();
        $tagsConfig = $tagsModule->LoadConfig();

        if (array_key_exists($name, $_POST) === false) {
            $tags = array();
        } else {
//            $tags = explode('|', str_replace($tagsConfig['xootags_delimiters'],'|', $tags) );
            $tags = explode(',', $_POST[$name]);
            $tags = array_filter(array_map('trim', $tags));
        }

        $tags_existing = $this->getByItem($itemid, $mid, true);
        $tags_delete   = array_diff(array_values($tags_existing), $tags);
        $tags_add      = array_diff($tags, array_values($tags_existing));

        if (!empty($tags_delete)) {
            $tags_delete = array_map(array($this->db, 'quoteString'), $tags_delete);
            $tag_ids     = $this->getIds(new Criteria('tag_term', '(' . implode(', ', $tags_delete) . ')', 'IN'));

            $tagsModule       = Xootags::getInstance();
            $tagsLinkHandler = $tagsModule->LinkHandler();

            if (!$tagsLinkHandler->deleteByIds($tag_ids, $itemid)) {
            }
            unset($tagsLinkHandler);

            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN'));
            $criteria->add(new Criteria('tag_count', 2, '<'));
            if (!parent::deleteAll($criteria)) {
            }

            /*
                        $criteria = new CriteriaCompo();
                        $criteria->add( new Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN') ) ;
                        if ( !parent::updateAll('tag_count', 'tag_count `1 1' , $criteria) ) {
                        }
            */
            $sql = 'UPDATE ' . $this->table . ' SET tag_count = tag_count - 1' . ' WHERE ' . $this->keyName . ' IN (' . implode(', ', $tag_ids) . ')';
            if (($result = $this->db->queryF($sql)) == false) {
                //xoops_error($this->db->error());
            }
        }

        if (!empty($tags_add)) {
            foreach ($tags_add as $tag) {
                $tag_update = false;
                if ($tagObj = $this->getObjects(new Criteria('tag_term', $tag))) {
                    $tagObj     = $tagObj[0];
                    $tag_id     = $tagObj->getVar('tag_id');
                    $tag_update = true;
                } else {
                    $tagObj = $this->create();
                    $tagObj->setVar('tag_term', $tag);
                    $tagObj->setVar('tag_count', 1);
                    $tagObj->setVar('tag_status', 1);
                    $tag_id = $this->insert($tagObj);
                }

                $tagsModule       = Xootags::getInstance();
                $tagsLinkHandler = $tagsModule->LinkHandler();

                $tagsLinkHandler->className = 'XootagsLink';
                $criteria                     = new CriteriaCompo();
                $criteria->add(new Criteria('tag_id', $tag_id));
                $criteria->add(new Criteria('tag_modid', $mid));
                $criteria->add(new Criteria('tag_itemid', $itemid));
                if (!$tagLink = $tagsLinkHandler->getObjects($criteria)) {
                    $tagLink = $tagsLinkHandler->create();
                    $tagLink->setVar('tag_id', $tag_id);
                    $tagLink->setVar('tag_modid', $mid);
                    $tagLink->setVar('tag_itemid', $itemid);
                    $tagLink->setVar('tag_time', time());
                    if (!$tagsLinkHandler->insert($tagLink)) {
                    }
                }

                if ($tag_update) {
                    $tagObj->setVar('tag_count', $tagObj->getVar('tag_count') + 1);
                    $this->insert($tagObj);
                }
            }
        }
        return null;
    }

    /**
     * @param int $itemid
     */
    public function deleteByItem($itemid = 0)
    {
        $this->updateByItem('tags', $itemid);
    }
}
