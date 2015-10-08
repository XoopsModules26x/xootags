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

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class XootagsTagsForm
 */
class XootagsTagsForm extends Xoops\Form\ThemeForm
{
    /**
     * @internal param null $obj
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        $xoops          = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();
        $module         = $module_handler->getByDirname('xooTags');

        return ($module && $module->getVar('isactive')) ? true : false;
    }

    /**
     * @param      $name
     * @param null $value
     * @param int  $size
     * @param int  $maxlength
     *
     * @return \Xoops\Form\Text|XoopsFormTags
     */
    public function tagsForm($name, $value = null, $size = 5, $maxlength = 10)
    {
        $xoops = Xoops::getInstance();

        $tagsModule = Xootags::getInstance();

        if ($this->isActive()) {
            $value = empty($value) ? '' : $value;

            if (!empty($value) && is_numeric($value)) {
                $modid = $xoops->module->getVar('mid');
                $value = '';
                $tagsLinkHandler = $tagsModule->LinkHandler();
                if ($tags = $tagsLinkHandler->getByItem($value, $modid, true)) {
                    $value = htmlspecialchars(implode(',', $tags));
                }
            }

            return new XoopsFormTags(_XOO_TAGS_TAGS, $name, $value, $size, $maxlength);
        } else {
            $xoops->logger->handleError(2, '<strong><span class="red">' . _XOO_TAGS_TAGS_ERROR . '</span></strong>', $xoops->getEnv('PHP_SELF'), 'TagsForm(...)');

            return new Xoops\Form\Text(_XOO_TAGS_TAGS, $name, $size, $maxlength, _XOO_TAGS_TAGS_ERROR);
        }
    }

    /**
     * Maintenance Form
     *
     * @param $module_id
     * @param $modules
     *
     * @return void
     */
    public function TagsFormModules($module_id, $modules)
    {
        $module_select = new Xoops\Form\Select(_AM_XOO_TAGS_MODULES, 'module_id', $module_id);
        $module_select->setExtra("onChange='javascript:window.location.href=\"tags.php?module_id=\" + this.value '");
        $module_select->addOption(0, _AM_XOO_TAGS_MODULES_ALL);
        $module_select->addOptionArray($modules);
        $this->addElement($module_select, true);
    }
}
