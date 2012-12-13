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

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';

XoopsLoad::load('xoopreferences', 'xootags');
$Tags_config = XooTagsPreferences::getInstance()->getConfig();

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

$script_name = basename($_SERVER['SCRIPT_NAME'], '.php');

XoopsLoad::load('system', 'system');
$system = System::getInstance();

$xoops = Xoops::getInstance();
if ($script_name != 'about') {
    $xoops->header('xootags_' . $script_name . '.html');
} else {
    $xoops->header();
}
$xoops->theme()->addStylesheet('modules/xootags/css/moduladmin.css');
$xoops->loadLanguage('main', 'xootags');

$admin_page = new XoopsModuleAdmin();
if ($script_name != 'about' && $script_name != 'index') {
    $admin_page->renderNavigation( basename($_SERVER['SCRIPT_NAME']) );
} elseif ($script_name != 'index') {
    $admin_page->displayNavigation( basename($_SERVER['SCRIPT_NAME']) );
}

$module_Handler = $xoops->getHandlerModule();
$xootags_tags_handler = $xoops->getModuleHandler('xootags_tags', 'xootags');
$xootags_link_handler = $xoops->getModuleHandler('xootags_link', 'xootags');

// Count by module
$criteria = new CriteriaCompo();
$criteria->setGroupby( 'tag_modid' );

$modules = array();
$count_items = 0;
$count_bymodule = $xootags_link_handler->getCounts( $criteria );
foreach ( $count_bymodule as $mid => $count_item ) {
    $count_items += $count_item;
    $module = $module_Handler->get( $mid ) ;
    $count_module[$mid]['mid'] = $mid;
    $count_module[$mid]['name'] = $module->getVar('name');
    $count_module[$mid]['count'] = $count_item;

    $modules[$mid] =  $module->getVar('name');
}
?>