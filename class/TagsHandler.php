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
 */
use Xoops\Core\Database\Connection;
use Xoops\Core\Request;

/**
 * Class TagsHandler
 */
class TagsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param null|\Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'xootags_tags', Tags::class, 'tag_id', 'tag_term');
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        $xoops = \Xoops::getInstance();
        $moduleHandler = $xoops->getHandlerModule();
        $module = $moduleHandler->getByDirname('xooTags');

        return ($module && $module->getVar('isactive')) ? true : false;
    }

    /**
     * @param $tag_id
     *
     * @return bool
     */
    public function SetOnline($tag_id)
    {
        if (0 != $tag_id) {
            $tag = $this->get($tag_id);
            if (1 == $tag->getVar('tag_status')) {
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
        $xoops = \Xoops::getInstance();
        $helper = \XoopsModules\Xootags\Helper::getInstance();
        if ($this->isActive()) {
            $xoops->theme()->addStylesheet('modules/xootags/assets/css/module.css');

            $linkHandler = $helper->LinkHandler();

            return $linkHandler->getbyItem($itemid, $modid, $onlyTags);
        }
        $xoops->logger->handleError(2, '<strong><span class="red">' . _XOO_TAGS_TAGS_ERROR . '</span></strong>', $xoops->getEnv('PHP_SELF'), 'TagsForm(...)');

        return false;
    }

    /**
     * @param string $name
     * @param int    $itemid
     *
     * @return string
     */
    public function updateByItem($name = 'tags', $itemid = 0)
    {
        $xoops = \Xoops::getInstance();
        $helper = \XoopsModules\Xootags\Helper::getInstance();

        $itemid = (int)($itemid);
        $mid = $xoops->module->getVar('mid');

        if (empty($itemid) || empty($mid) || null === $name) {
            return _XOO_TAGS_ADD_TAGS_ERROR;
        }

        $helper = \XoopsModules\Xootags\Helper::getInstance();
        $tagsConfig = $helper->loadConfig();

        if (false === array_key_exists($name, $_POST)) {
            $tags = [];
        } else {
//            $tags = explode('|', str_replace($tagsConfig['xootags_delimiters'],'|', $tags) );
            $tags = explode(',', Request::getString($name, '', 'POST')); //$_POST[$name]);
            $tags = array_filter(array_map('trim', $tags));
        }

        $tags_existing = $this->getByItem($itemid, $mid, true);
        $tags_delete = array_diff(array_values($tags_existing), $tags);
        $tags_add = array_diff($tags, array_values($tags_existing));

        if (!empty($tags_delete)) {
            $tags_delete = array_map([$this->db, 'quoteString'], $tags_delete);
            $tag_ids = $this->getIds(new \Criteria('tag_term', '(' . implode(', ', $tags_delete) . ')', 'IN'));

            $helper = \XoopsModules\Xootags\Helper::getInstance();
            $linkHandler = $helper->LinkHandler();

            if (!$linkHandler->deleteByIds($tag_ids, $itemid)) {
            }
            unset($linkHandler);

            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN'));
            $criteria->add(new \Criteria('tag_count', 2, '<'));
            if (!parent::deleteAll($criteria)) {
            }

            /*
                        $criteria = new \CriteriaCompo();
                        $criteria->add( new \Criteria('tag_id', '(' . implode(', ', $tag_ids) . ')', 'IN') ) ;
                        if ( !parent::updateAll('tag_count', 'tag_count `1 1' , $criteria) ) {
                        }
            */
            $sql = 'UPDATE ' . $this->table . ' SET tag_count = tag_count - 1' . ' WHERE ' . $this->keyName . ' IN (' . implode(', ', $tag_ids) . ')';
            if (false === ($result = $this->db->queryF($sql))) {
                //xoops_error($this->db->error());
            }
        }

        if (!empty($tags_add)) {
            foreach ($tags_add as $tag) {
                $tag_update = false;
                if ($tagObj = $this->getObjects(new \Criteria('tag_term', $tag))) {
                    $tagObj = $tagObj[0];
                    $tag_id = $tagObj->getVar('tag_id');
                    $tag_update = true;
                } else {
                    $tagObj = $this->create();
                    $tagObj->setVar('tag_term', $tag);
                    $tagObj->setVar('tag_count', 1);
                    $tagObj->setVar('tag_status', 1);
                    $tag_id = $this->insert($tagObj);
                }

                $helper = \XoopsModules\Xootags\Helper::getInstance();
                $linkHandler = $helper->LinkHandler();

                $linkHandler->className = 'Link';
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('tag_id', $tag_id));
                $criteria->add(new \Criteria('tag_modid', $mid));
                $criteria->add(new \Criteria('tag_itemid', $itemid));
                if (!$tagLink = $linkHandler->getObjects($criteria)) {
                    $tagLink = $linkHandler->create();
                    $tagLink->setVar('tag_id', $tag_id);
                    $tagLink->setVar('tag_modid', $mid);
                    $tagLink->setVar('tag_itemid', $itemid);
                    $tagLink->setVar('tag_time', time());
                    if (!$linkHandler->insert($tagLink)) {
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
