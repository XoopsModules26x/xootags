<?php
/**
 * Xootags module
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
 * @package         Xootags
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';
include dirname(__FILE__) . '/include/functions.php';

$op = '';
if ( isset( $_POST ) ){
    foreach ( $_POST as $k => $v )  {
        ${$k} = $v;
    }
}
if ( isset( $_GET ) ){
    foreach ( $_GET as $k => $v )  {
        ${$k} = $v;
    }
}

XoopsLoad::load('system', 'system');
$system = System::getInstance();

$xoops = Xoops::getInstance();

$script_name = basename($_SERVER['SCRIPT_NAME'], '.php');
$xoops->header('xootags_' . $script_name . '.html');

$xoops->theme()->addStylesheet('modules/xootags/css/module.css');

$tags_module = Xootags::getInstance();
$tags_config = $tags_module->LoadConfig();
$tags_link_handler = $tags_module->LinkHandler();
$tags_tags_handler = $tags_module->TagsHandler();
$module_Handler = $xoops->getHandlerModule();

$xoops->tpl()->assign('moduletitle', $xoops->module->name() );
$xoops->tpl()->assign('welcome', $tags_config['xootags_welcome'] );
$xoops->tpl()->assign('xootags_colors', implode(',', $tags_config['xootags_colors']) );
$xoops->tpl()->assign('xootags_count', $tags_config['xootags_count'] );
$xoops->tpl()->assign('xootags_main_mode', $tags_config['xootags_main_mode'] );
?>