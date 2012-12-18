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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XooTagsCorePreload extends XoopsPreloadItem
{    static public function eventCoreIncludeCommonStart($args)
    {
        $xoops = xoops::getinstance();
        $xoops->registry()->set('XOOTAGS', false);
        if (XooTagsCorePreload::isActive()) {
            $xoops->registry()->set('XOOTAGS', true);
            XoopsLoad::addMap(array('xoopsformtags' => dirname(dirname(__FILE__)) . '/class/xoopsformtags.php'));
        }
    }

    static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'xootags' => $path . '/class/xootags.php',
        ));
    }

    static private function isActive()
    {        $xoops = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();
        $module = $module_handler->getByDirname('xooTags');
        return ($module && $module->getVar('isactive')) ? true : false;
    }
}
?>