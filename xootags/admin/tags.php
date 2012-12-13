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
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

include dirname(__FILE__) . '/header.php';

switch ($op) {    case 'del':
    $tag_id = $system->CleanVars($_REQUEST, 'tag_id', 0, 'int');
    if( isset($tag_id) && $tag_id > 0 ){
        if ($tag = $xootags_tags_handler->get($tag_id) ) {
            $delete = $system->CleanVars( $_POST, 'ok', 0, 'int' );
            if ($delete == 1) {
                if ( !$xoops->security()->check() ) {
                    $xoops->redirect('tags.php', 5, implode(',', $xoops->security()->getErrors()));
                }
                $xootags_link_handler->DeleteByItem( $tag_id );
                $xootags_tags_handler->delete($tag);
                $xoops->redirect('tags.php', 5, _AM_XOO_TAGS_DELETED);
            } else {
                $xoops->confirm(array('ok' => 1, 'tag_id' => $tag_id, 'op' => 'del'), $_SERVER['REQUEST_URI'], sprintf(_AM_XOO_TAGS_DELETE_CFM . "<br /><b><span style='color : Red'> %s </span></b><br /><br />", $tag->getVar('tag_term')));
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
    $tag_id = $system->CleanVars($_REQUEST, 'tag_id', 0, 'int');
    $xootags_tags_handler->SetOnline($tag_id);
    $xoops->redirect('tags.php', 5, _AM_XOO_TAGS_SAVED);
    break;

    default:
    $module_id = $system->CleanVars($_REQUEST, 'module_id', 0, 'int');

    $form = $xoops->getModuleForm(0, 'tags', 'xootags');
    $form->TagsFormModules( $module_id, $modules );

    if ($module_id == 0 ) {        $criteria = new CriteriaCompo();
        $criteria->setSort('tag_count');
        $criteria->setOrder('DESC');
        $tags = $xootags_tags_handler->getObjects($criteria, false, false);
    } else {        $tags = $xootags_link_handler->getbyModule( $module_id );
    }
    $xoops->tpl()->assign('form', $form->render() );
    $xoops->tpl()->assign('tags', $tags);
}

include dirname(__FILE__) . '/footer.php';
?>