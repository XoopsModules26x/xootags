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
use Xoops\Core\Request;

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

$helper = \XoopsModules\Xootags\Helper::getInstance();
$tagsConfig = $helper->loadConfig();

$op = '';
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        ${$k} = $v;
    }
}

$scriptName = basename(Request::getString('SCRIPT_NAME', '', 'SERVER'), '.php');

\XoopsLoad::load('system', 'system');
$system = \System::getInstance();

$xoops = \Xoops::getInstance();
if ('about' !== $scriptName) {
    $xoops->header('xootags_' . $scriptName . '.tpl');
} else {
    $xoops->header();
}
$xoops->theme()->addStylesheet('modules/xootags/assets/css/moduladmin.css');

$adminPage = new \Xoops\Module\Admin();
if ('about' !== $scriptName && 'index' !== $scriptName) {
    $adminPage->renderNavigation(basename(Request::getString('SCRIPT_NAME', '', 'SERVER'))); // $_SERVER['SCRIPT_NAME']));
} elseif ('index' !== $scriptName) {
    $adminPage->displayNavigation(basename(Request::getString('SCRIPT_NAME', '', 'SERVER'))); //$_SERVER['SCRIPT_NAME']));
}

$helper = \XoopsModules\Xootags\Helper::getInstance();
$tagsConfig = $helper->loadConfig();
$tagsLinkHandler = $helper->linkHandler();
$tagsTagsHandler = $helper->tagsHandler();

$moduleHandler = $xoops->getHandlerModule();

// Count by module
$criteria = new \CriteriaCompo();
$criteria->setGroupBy('tag_modid');

$modules = [];
$countItems = 0;
$countByModule = $tagsLinkHandler->getCounts($criteria);
foreach ($countByModule as $mid => $count_item) {
    $countItems += $count_item;
    $module = $moduleHandler->get($mid);
    $countModule[$mid]['mid'] = $mid;
    $countModule[$mid]['name'] = $module->getVar('name');
    $countModule[$mid]['count'] = $count_item;

    $modules[$mid] = $module->getVar('name');
}
