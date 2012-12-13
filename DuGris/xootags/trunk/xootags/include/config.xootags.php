<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');

return $config = array (
    'xootags_welcome'            => '',
    'xootags_main_mode'          => 'cloud',

    'xootags_count'              => true,
    'xootags_limit_tag_main'     => 100,
    'xootags_font_min'           => 100,
    'xootags_font_max'           => 200,

/*
    'xootags_main_menu'          => 0,
    'xootags_limit_tag_menu'     => 10,
    'xootags_delimiters'         => '|',
*/

    'xootags_colors'             => array(
        'Aqua', 'Black', 'Blue', 'Fuchsia', 'Gray', 'Green', 'Lime', 'Maroon',
        'Navy', 'Olive', 'Purple', 'Red', 'Silver', 'Teal', 'White', 'Yellow',
    ),

    'xootags_qrcode'    => array(
        'use_qrcode'        => 0,
        'CorrectionLevel'   => 1,
        'matrixPointSize'   => 2,
        'whiteMargin'       => 0,
        'backgroundColor'   => 'White',
        'foregroundColor'   => 'Black',
    ),
);
?>