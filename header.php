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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xootags
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 */
use Xoops\Core\Request;

include dirname(dirname(__DIR__)) . '/mainfile.php';

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

\XoopsLoad::load('system', 'system');
$system = \System::getInstance();

$xoops = \Xoops::getInstance();

$scriptName = basename(Request::getString('SCRIPT_NAME', '', 'SERVER'), '.php'); //$_SERVER['SCRIPT_NAME'], '.php');
$xoops->header('xootags_' . $scriptName . '.tpl');

$xoops->theme()->addStylesheet('modules/xootags/assets/css/module.css');

$helper = \XoopsModules\Xootags\Helper::getInstance();
$tagsConfig = $helper->loadConfig();
$linkHandler = $helper->getHandler('Link');
$tagsHandler = $helper->getHandler('Tags');
$moduleHandler = $xoops->getHandlerModule();

$xoops->tpl()->assign('moduletitle', $xoops->module->name());
$xoops->tpl()->assign('welcome', $tagsConfig['xootags_welcome']);
$xoops->tpl()->assign('xootags_colors', implode(',', $tagsConfig['xootags_colors']));
$xoops->tpl()->assign('xootags_count', $tagsConfig['xootags_count']);
$xoops->tpl()->assign('xootags_main_mode', $tagsConfig['xootags_main_mode']);
