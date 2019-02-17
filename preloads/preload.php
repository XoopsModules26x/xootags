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
 * @version         $Id: core.php 1429 2013-01-15 01:11:29Z DuGris $
 */
use Xoops\Core\PreloadItem;

/**
 * Class XooTagsPreload
 */
class XooTagsPreload extends PreloadItem
{
    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonStart($args)
    {
        $xoops = \Xoops::getInstance();
        $xoops->registry()->set('XOOTAGS', false);
        if (self::isActive()) {
            $xoops->registry()->set('XOOTAGS', true);
            \XoopsLoad::addMap(['xoopsformtags' => dirname(__DIR__) . '/class/xoopsformtags.php']);
        }
    }

    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        require_once __DIR__ . '/autoloader.php';
    }

    /**
     * @return bool
     */
    private static function isActive()
    {
        $xoops = \Xoops::getInstance();
        $moduleHandler = $xoops->getHandlerModule();
        $module = $moduleHandler->getByDirname('xootags');

        return ($module && $module->getVar('isactive')) ? true : false;
    }
}
