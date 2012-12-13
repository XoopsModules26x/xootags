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

class XooTagsPreferencesForm extends XoopsThemeForm
{
    private $_config = array();

    private $_colors = array(
        'Aqua'    => '#00FFFF',
        'Black'   => '#000000',
        'Blue'    => '#0000FF',
        'Fuchsia' => '#FF00FF',
        'Gray'    => '#808080',
        'Green'   => '#008000',
        'Lime'    => '#00FF00',
        'Maroon'  => '#800000',
        'Navy'    => '#000080',
        'Olive'   => '#808000',
        'Purple'  => '#800080',
        'Red'     => '#FF0000',
        'Silver'  => '#C0C0C0',
        'Teal'    => '#008080',
        'White'   => '#FFFFFF',
        'Yellow'  => '#FFFF00',
    );

    /**
     * @param null $obj
     */
    public function __construct()
    {        $this->_config = XooTagsPreferences::getInstance()->loadConfig();
    }

    /**
     * Maintenance Form
     * @return void
     */
    public function PreferencesForm()
    {        extract( $this->_config );
        parent::__construct('', 'form_preferences', 'preferences.php', 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $this->insertBreak(_XOO_CONFIG_MAINPAGE,'preferenceTitle');

        //welcome
        $this->addElement( new XoopsFormTextArea(_XOO_CONFIG_WELCOME, 'xootags_welcome', $xootags_welcome, 12, 12) );

        // Display count
        $this->addElement( new XoopsFormRadioYN(_XOO_CONFIG_COUNT, 'xootags_count', $xootags_count) );

        // Display mode
        $category_mode = new XoopsFormSelect(_XOO_CONFIG_DISPLAY_MODE, 'xootags_main_mode', $xootags_main_mode);
        $category_mode->addOption('blog',   _XOO_CONFIG_MODE_BLOG);
        $category_mode->addOption('cloud',  _XOO_CONFIG_MODE_CLOUD);
        $this->addElement( $category_mode );
        // limit per page
        $this->addElement( new XoopsFormText(_XOO_CONFIG_LIMIT_TAG_MAIN, 'xootags_limit_tag_main', 1, 10, $xootags_limit_tag_main) );
        // font size
        $this->addElement( new XoopsFormText(_XOO_CONFIG_FONT_MAX, 'xootags_font_max', 1, 10, $xootags_font_max) );
        $this->addElement( new XoopsFormText(_XOO_CONFIG_FONT_MIN, 'xootags_font_min', 1, 10, $xootags_font_min) );

//        $this->insertBreak(_XOO_CONFIG_MAINMENU,'preferenceTitle');
        // main menu
//        $this->addElement( new XoopsFormRadioYN(_XOO_CONFIG_MAIN_MENU, 'xootags_main_menu', $xootags_main_menu) );
        // limit per menu
//        $this->addElement( new XoopsFormText(_XOO_CONFIG_ITEM_PERMENU, 'xootags_limit_tag_menu', 1, 10, $xootags_limit_tag_menu) );

        // Colors
        $this->insertBreak(_XOO_CONFIG_COLORS,'preferenceTitle');

        $colors_tray = new XoopsFormElementTray(_XOO_CONFIG_COLORS, '' );
        $colors_select = new XoopsFormSelect('', 'xootags_colors', $xootags_colors, 5, true);
        $extra = '';
        foreach ( $this->_colors as $k => $color ) {            $colors_select->addOption( $k );
            $extra .= '<div class="color-div"><div class="color-box" style="background-color:' . $color . ';"></div>' . $k . '</div>';
        }

        $colors_tray->addElement( $colors_select );
        $colors_tray->addElement( new XoopsFormLabel( '', '<div class="colors">' . $extra . '</div>' ) );
        $this->addElement( $colors_tray );

        // Delimiters
/*
        $this->insertBreak(_XOO_CONFIG_DELIMITERS,'preferenceTitle');
        $this->addElement( new XoopsFormText(_XOO_CONFIG_DELIMITERS, 'xootags_delimiters', 1, 10, $xootags_delimiters) );
*/

        // QR code
        $this->QRcodeForm();

        // button
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'save'));
        $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $button_tray->addElement(new XoopsFormButton('', 'reset', _RESET, 'reset'));
        $cancel_send = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
        $cancel_send->setExtra("onclick='javascript:history.go(-1);'");
        $button_tray->addElement($cancel_send);
        $this->addElement($button_tray);
    }

    private function QRcodeForm()
    {        $xoops = Xoops::getinstance();
        if ( $xoops->isActiveModule('qrcode') ) {
            $xoops->theme()->addScript('modules/xootags/include/qrcode.js');
            extract( $this->_config );
            $this->insertBreak(_XOO_CONFIG_QRCODE,'preferenceTitle');

            // use QR code
            $this->addElement( new XoopsFormRadioYN(_XOO_CONFIG_QRCODE_USE, 'xootags_qrcode[use_qrcode]', $xootags_qrcode['use_qrcode']) );

            // Error Correction Level
            $ecl_mode = new XoopsFormSelect(_XOO_CONFIG_QRCODE_ECL, 'xootags_qrcode[CorrectionLevel]', $xootags_qrcode['CorrectionLevel']);
            $ecl_mode->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xootags' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );
            $ecl_mode->addOption(0,   _XOO_CONFIG_QRCODE_ECL_L);
            $ecl_mode->addOption(1,   _XOO_CONFIG_QRCODE_ECL_M);
            $ecl_mode->addOption(2,   _XOO_CONFIG_QRCODE_ECL_Q);
            $ecl_mode->addOption(3,   _XOO_CONFIG_QRCODE_ECL_H);
            $this->addElement( $ecl_mode );

            // Matrix Point Size
            $matrix_mode = new XoopsFormSelect(_XOO_CONFIG_QRCODE_MATRIX, 'xootags_qrcode[matrixPointSize]', $xootags_qrcode['matrixPointSize']);
            $matrix_mode->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xootags' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );
            for ($i = 1; $i <= 5; $i++) {
                $matrix_mode->addOption($i, $i);
            }
            $this->addElement( $matrix_mode );

            // Margin
            $margin_mode = new XoopsFormSelect(_XOO_CONFIG_QRCODE_MARGIN, 'xootags_qrcode[whiteMargin]', $xootags_qrcode['whiteMargin']);
            $margin_mode->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xootags' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );
            for ($i = 0; $i <= 20; $i++) {
                $margin_mode->addOption($i,   $i);
            }
            $this->addElement( $margin_mode );

            // Background & Foreground Color
            $colors_tray = new XoopsFormElementTray(_XOO_CONFIG_QRCODE_COLORS, '' );

            $colors_bg = new XoopsFormSelect(_XOO_CONFIG_QRCODE_COLORS_BG . ': ', 'xootags_qrcode[backgroundColor]', $xootags_qrcode['backgroundColor'], 1);
            $colors_bg->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xootags' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );

            $colors_fg = new XoopsFormSelect(_XOO_CONFIG_QRCODE_COLORS_FG . ': ', 'xootags_qrcode[foregroundColor]', $xootags_qrcode['foregroundColor'], 1);
            $colors_fg->setExtra( "onchange='showImgQRcode(\"image_qrcode\", \"" . 'xootags' . "\", \"url=http://dugris.xoofoo.org\", \"" . $xoops->url('modules') . "\")'" );

            foreach ( $this->_colors as $k => $color ) {
                $colors_bg->addOption( $k );
                $colors_fg->addOption( $k );
            }
            $colors_tray->addElement( new XoopsFormLabel( '', "<div class='floatright'><img src='" . $xoops->url('/modules/xootags/') . "qrcode.php?url=http://dugris.xoofoo.org' name='image_qrcode' id='image_qrcode' alt='" . _XOO_CONFIG_QRCODE . "' /></div>" ) );
            $colors_tray->addElement( $colors_bg );
            $colors_tray->addElement( new XoopsFormLabel( '', '<br />') );
            $colors_tray->addElement( $colors_fg );

            $this->addElement( $colors_tray );
        } else {
            $this->addElement( new XoopsFormHidden('xootags_qrcode[use_qrcode]', 0) );
        }
    }
}
?>