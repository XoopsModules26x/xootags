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
 */

include __DIR__ . '/header.php';

switch ($op) {
    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('preferences.php', 3, implode('<br />', $xoops->security()->getErrors()));
        }

        // Write configuration file
        $object = new XooTagsPreferences();
        $object->writeConfig($object->prepare2Save());
        $xoops->redirect('preferences.php', 3, _XOO_CONFIG_SAVED);
        break;

    default:
        $form = $tagsModule->getForm($tagsConfig, 'preferences');
        $form->display();
}
include __DIR__ . '/footer.php';
