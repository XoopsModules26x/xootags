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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         xootags
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */
use Xoops\Core\Request;
use XoopsModules\Xootags\Form;

include __DIR__ . '/header.php';

switch ($op) {
    case 'del':
        $tag_id = Request::getInt('tag_id', 0); //$system->cleanVars($_REQUEST, 'tag_id', 0, 'int');
        if (isset($tag_id) && $tag_id > 0) {
            if ($tag = $tagsHandler->get($tag_id)) {
                $delete = Request::getInt('ok', 0, 'POST'); //$system->cleanVars($_POST, 'ok', 0, 'int');
                if (1 == $delete) {
                    if (!$xoops->security()->check()) {
                        $xoops->redirect('tags.php', 5, implode(',', $xoops->security()->getErrors()));
                    }
                    $linkHandler->deleteByItem($tag_id);
                    $tagsHandler->delete($tag);
                    $xoops->redirect('tags.php', 5, _AM_XOO_TAGS_DELETED);
                } else {
                    $xoops->confirm(['ok' => 1, 'tag_id' => $tag_id, 'op' => 'del'], Request::getString('REQUEST_URI', '', 'SERVER'), //$_SERVER['REQUEST_URI'],
                                    sprintf(_AM_XOO_TAGS_DELETE_CFM . "<br><b><span style='color : #ff0000'> %s </span></b><br><br>", $tag->getVar('tag_term')));
                }
            } else {
                $xoops->redirect('tags.php', 5);
            }
        } else {
            $xoops->redirect('tags.php', 5);
        }
        break;
    case 'show':
    case 'hide':
        $tag_id = Request::getInt('tag_id', 0); //$system->cleanVars($_REQUEST, 'tag_id', 0, 'int');
        $tagsHandler->SetOnline($tag_id);
        $xoops->redirect('tags.php', 5, _AM_XOO_TAGS_SAVED);
        break;
    default:
        $module_id = Request::getInt('module_id', 0); //$system->cleanVars($_REQUEST, 'module_id', 0, 'int');

//        $form = $helper->getForm(null, 'tags');
        $form = new Form\TagsForm();
        $form->TagsFormModules($module_id, $modules);

        if (0 == $module_id) {
            $criteria = new \CriteriaCompo();
            $criteria->setSort('tag_count');
            $criteria->setOrder('DESC');
            $tags = $tagsHandler->getObjects($criteria, false, false);
        } else {
            $tags = $linkHandler->getbyModule($module_id);
        }
        $xoops->tpl()->assign('form', $form->render());
        $xoops->tpl()->assign('tags', $tags);
}

include __DIR__ . '/footer.php';
