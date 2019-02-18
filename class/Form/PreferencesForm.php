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
 * Class PreferencesForm
 */
class PreferencesForm extends \Xoops\Form\ThemeForm
{
    private $config = [];

    private $colors
        = [
            'Aqua' => '#00FFFF',
            'Black' => '#000000',
            'Blue' => '#0000FF',
            'Fuchsia' => '#FF00FF',
            'Gray' => '#808080',
            'Green' => '#008000',
            'Lime' => '#00FF00',
            'Maroon' => '#800000',
            'Navy' => '#000080',
            'Olive' => '#808000',
            'Purple' => '#800080',
            'Red' => '#FF0000',
            'Silver' => '#C0C0C0',
            'Teal' => '#008080',
            'White' => '#FFFFFF',
            'Yellow' => '#FFFF00',
        ];

    /**
     * @param string $config
     *
     * @internal param null $obj
     */
    public function __construct($config)
    {
        extract($config);
        parent::__construct('', 'form_preferences', 'preferences.php', 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $tabTray = new \Xoops\Form\TabTray('', 'uniqueid');

        /**
         * Main page
         */
        $tab1 = new \Xoops\Form\Tab(_XOO_CONFIG_MAINPAGE, 'tabid-1');
        //welcome
        $tab1->addElement(new \Xoops\Form\TextArea(_XOO_CONFIG_WELCOME, 'xootags_welcome', $xootags_welcome, 12, 12));

        // Display mode
        $category_mode = new \Xoops\Form\Select(_XOO_CONFIG_DISPLAY_MODE, 'xootags_main_mode', $xootags_main_mode);
        $category_mode->addOption('blog', _XOO_CONFIG_MODE_BLOG);
        $category_mode->addOption('cloud', _XOO_CONFIG_MODE_CLOUD);
        $tab1->addElement($category_mode);

        // Display count
        $tab1->addElement(new \Xoops\Form\RadioYesNo(_XOO_CONFIG_COUNT, 'xootags_count', $xootags_count));

        // limit per page : main page
        $tab1->addElement(new \Xoops\Form\Text(_XOO_CONFIG_LIMIT_MAIN, 'xootags_limit_tag_main', 1, 10, $xootags_limit_tag_main));

        // limit per page : main page
        $tab1->addElement(new \Xoops\Form\Text(_XOO_CONFIG_LIMIT_TAGS, 'xootags_limit_tag_tag', 1, 10, $xootags_limit_tag_tag));

        $tabTray->addElement($tab1);

        /**
         * Tags
         */
        $tab2 = new \Xoops\Form\Tab(_XOO_CONFIG_TAGS, 'tabid-2');
        // font size
        $tab2->addElement(new \Xoops\Form\Text(_XOO_CONFIG_FONT_MAX, 'xootags_font_max', 1, 10, $xootags_font_max));
        $tab2->addElement(new \Xoops\Form\Text(_XOO_CONFIG_FONT_MIN, 'xootags_font_min', 1, 10, $xootags_font_min));

        $colors_tray = new \Xoops\Form\ElementTray(_XOO_CONFIG_COLORS, '');
        $colors_select = new \Xoops\Form\Select('', 'xootags_colors', $xootags_colors, 5, true);
        $extra = '';
        foreach ($this->colors as $k => $color) {
            $colors_select->addOption($k);
            $extra .= '<div class="color-div"><div class="color-box" style="background-color:' . $color . ';"></div>' . $k . '</div>';
        }

        $colors_tray->addElement($colors_select);
        $colors_tray->addElement(new \Xoops\Form\Label('', '<div class="colors">' . $extra . '</div>'));
        $tab2->addElement($colors_tray);

        $tabTray->addElement($tab2);

        $this->addElement($tabTray);

        /**
         * Buttons
         */
        $buttonTray = new \Xoops\Form\ElementTray('', '');
        $buttonTray->addElement(new \Xoops\Form\Hidden('op', 'save'));

        $buttonSubmit = new \Xoops\Form\Button('', 'submit', \XoopsLocale::A_SUBMIT, 'submit');
        $buttonSubmit->setClass('btn btn-success');
        $buttonTray->addElement($buttonSubmit);

        $buttonReset = new \Xoops\Form\Button('', 'reset', \XoopsLocale::A_RESET, 'reset');
        $buttonReset->setClass('btn btn-warning');
        $buttonTray->addElement($buttonReset);

        $buttonCancel = new \Xoops\Form\Button('', 'cancel', \XoopsLocale::A_CANCEL, 'button');
        $buttonCancel->setExtra("onclick='javascript:history.go(-1);'");
        $buttonCancel->setClass('btn btn-danger');
        $buttonTray->addElement($buttonCancel);

        $this->addElement($buttonTray);
    }
}
