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
 * @author          Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author          Laurent JEN (Aka DuGris)

 */
$i = 1;
$adminmenu[$i]['title'] = _MI_XOO_TAGS_INDEX;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['icon'] = 'home.png';

$xoops = \Xoops::getInstance();
if ($xoops->isAdmin()) {
    ++$i;
    $adminmenu[$i]['title'] = _MI_XOO_TAGS_PREFERENCES;
    $adminmenu[$i]['link'] = 'admin/preferences.php';
    $adminmenu[$i]['icon'] = 'administration.png';
}

++$i;
$adminmenu[$i]['title'] = _MI_XOO_TAGS_EDIT;
$adminmenu[$i]['link'] = 'admin/tags.php';
$adminmenu[$i]['icon'] = 'tags.png';

++$i;
$adminmenu[$i]['title'] = _MI_XOO_TAGS_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';
