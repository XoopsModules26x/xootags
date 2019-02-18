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
include __DIR__ . '/header.php';

$xoops->tpl()->assign('countModule', $countModule);
$xoops->tpl()->assign('count_item', $countItems);
$xoops->tpl()->assign('count_tag', $tagsHandler->getCount());
$adminPage->addInfoBox(_AM_XOO_TAGS_STATS);
$adminPage->addInfoBoxLine($xoops->tpl()->fetch('admin:xootags/xootags_tags_stats.tpl'));

// extension
$adminPage->addConfigBoxLine(['qrcode', 'warning'], 'extension');
$adminPage->addConfigBoxLine(['notifications', 'warning'], 'module');

include __DIR__ . '/footer.php';
