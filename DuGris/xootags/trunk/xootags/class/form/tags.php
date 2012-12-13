<?php
/**
 * Xooghost module
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
 * @package         Xooghost
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XootagsTagsForm extends XoopsThemeForm
{
    /**
     * @param null $obj
     */
    public function __construct()
    {    }

    private function isActive()
    {
        $xoops = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();
        $module = $module_handler->getByDirname('xooTags');
        return ($module && $module->getVar('isactive')) ? true : false;
    }

    public function TagsForm( $name, $value = null, $size=5, $maxlength=10 )
    {        $xoops = xoops::getinstance();
        $xoops->loadLanguage('main', 'xootags');
        if ( $this->isActive() ) {            XoopsLoad::load('xoopreferences', 'xootags');
            $Tags_config = XooTagsPreferences::getInstance()->getConfig();

            $value = empty($value) ? '' : $value;

            if ( !empty($value) && is_numeric($value) ) {
                $modid = $xoops->module->getVar('mid');
                $xootags_link_handler = $xoops->getModuleHandler('xootags_link', 'xootags');
                if ( $tags = $xootags_link_handler->getByItem($value, $modid, true) ) {                    $value = htmlspecialchars(implode(',', $tags));
                } else {
                    $value = '';
                }
            }
            return new XoopsFormTags(_XOO_TAGS_TAGS, $name, $value, $size, $maxlength);
        } else {            $xoops->logger->handleError( 2 , '<strong><span class="red">' . _XOO_TAGS_TAGS_ERROR . '</span></strong>', $xoops->getenv('PHP_SELF'), 'TagsForm(...)' );
            return new XoopsFormText(_XOO_TAGS_TAGS, $name, $size, $maxlength, _XOO_TAGS_TAGS_ERROR) ;
        }
    }

    /**
     * Maintenance Form
     * @return void
     */
    public function TagsFormModules( $module_id, $modules )
    {        $module_select = new XoopsFormSelect(_AM_XOO_TAGS_MODULES, 'module_id',  $module_id);
        $module_select->setExtra("onChange='javascript:window.location.href=\"tags.php?module_id=\" + this.value '");
        $module_select->addOption( 0, _AM_XOO_TAGS_MODULES_ALL );
        $module_select->addOptionArray( $modules);
        $this->addElement($module_select, true);
    }
}
?>