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

$xoops->tpl()->assign('count_module', $count_module );
$xoops->tpl()->assign('count_item', $count_items );
$xoops->tpl()->assign('count_tag', $xootags_tags_handler->getCount() );
$admin_page->addInfoBox(_AM_XOO_TAGS_STATS);
$admin_page->addInfoBoxLine( $xoops->tpl()->fetch('admin:xootags|xootags_tags_stats.html') );

include dirname(__FILE__) . '/footer.php';
?>