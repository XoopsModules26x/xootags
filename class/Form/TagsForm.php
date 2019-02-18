<?php

namespace XoopsModules\Xootags\Form;

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

/**
 * Class TagsForm
 */
class TagsForm extends \Xoops\Form\ThemeForm
{
    /**
     * TagsForm constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    private function isActive()
    {
        $xoops = \Xoops::getInstance();
        $moduleHandler = $xoops->getHandlerModule();
        $module = $moduleHandler->getByDirname('xooTags');

        return ($module && $module->getVar('isactive')) ? true : false;
    }

    /**
     * @param      $name
     * @param null $value
     * @param int  $size
     * @param int  $maxlength
     *
     * @return \Xoops\Form\Text|\XoopsModules\Xootags\XoopsFormTags
     */
    public function tagForm($name, $value = null, $size = 5, $maxlength = 10)
    {
        $xoops = \Xoops::getInstance();

        $helper = \XoopsModules\Xootags\Helper::getInstance();

        if ($this->isActive()) {
            $value = empty($value) ? '' : $value;

            if (!empty($value) && is_numeric($value)) {
                $modid = $xoops->module->getVar('mid');
                $value = '';
                $linkHandler = $helper->LinkHandler();
                if ($tags = $linkHandler->getByItem($value, $modid, true)) {
                    $value = htmlspecialchars(implode(',', $tags));
                }
            }

            return new \XoopsModules\Xootags\XoopsFormTags(_XOO_TAGS_TAGS, $name, $value, $size, $maxlength);
        }
        $xoops->logger->handleError(2, '<strong><span class="red">' . _XOO_TAGS_TAGS_ERROR . '</span></strong>', $xoops->getEnv('PHP_SELF'), 'TagsForm(...)');

        return new \Xoops\Form\Text(_XOO_TAGS_TAGS, $name, $size, $maxlength, _XOO_TAGS_TAGS_ERROR);
    }

    /**
     * Maintenance Form
     *
     * @param $module_id
     * @param $modules
     */
    public function TagsFormModules($module_id, $modules)
    {
        $moduleSelect = new \Xoops\Form\Select(_AM_XOO_TAGS_MODULES, 'module_id', $module_id);
        $moduleSelect->setExtra("onChange='javascript:window.location.href=\"tags.php?module_id=\" + this.value '");
        $moduleSelect->addOption(0, _AM_XOO_TAGS_MODULES_ALL);
        $moduleSelect->addOptionArray($modules);
        $this->addElement($moduleSelect, true);
    }
}
