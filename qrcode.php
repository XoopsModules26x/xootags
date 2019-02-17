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
use Xoops\Core\Request;

include __DIR__ . '/header.php';
$url = Request::getString('url', ''); //$system->cleanVars($_REQUEST, 'url', '', 'string');
extract($tagsConfig['xootags_qrcode']);

if (count($_GET) > 1) {
    //    if (isset($_GET['bgcolor'])) {
    //        $xoops->registry()->set('TAGS_BGCOLOR', $_GET['bgcolor']);
    //    }

    $xoops->registry()->set('TAGS_BGCOLOR', Request::getString('bgcolor', '', 'GET'));

    $backgroundColor = ($xoops->registry()->offsetExists('TAGS_BGCOLOR')) ? $xoops->registry()->get('TAGS_BGCOLOR') : $backgroundColor;

    //    if (isset($_GET['fgcolor'])) {
    //        $xoops->registry()->set('TAGS_FGCOLOR', $_GET['fgcolor']);
    //    }

    $xoops->registry()->set('TAGS_FGCOLOR', Request::getString('fgcolor', '', 'GET'));
    $foregroundColor = ($xoops->registry()->offsetExists('TAGS_FGCOLOR')) ? $xoops->registry()->get('TAGS_FGCOLOR') : $foregroundColor;

    //    if (isset($_GET['margin'])) {
    //        $xoops->registry()->set('TAGS_MARGIN', $_GET['margin']);
    //    }
    //

    $xoops->registry()->set('TAGS_MARGIN', Request::getString('margin', '', 'GET'));
    $whiteMargin = ($xoops->registry()->offsetExists('TAGS_MARGIN')) ? $xoops->registry()->get('TAGS_MARGIN') : $whiteMargin;

    //    if (isset($_GET['correction'])) {
    //        $xoops->registry()->set('TAGS_CORRECTION', $_GET['correction']);
    //    }

    $xoops->registry()->set('TAGS_CORRECTION', Request::getString('correction', '', 'GET'));
    $CorrectionLevel = ($xoops->registry()->offsetExists('TAGS_CORRECTION')) ? $xoops->registry()->get('TAGS_CORRECTION') : $CorrectionLevel;

    //    if (isset($_GET['size'])) {
    //        $xoops->registry()->set('TAGS_SIZE', $_GET['size']);
    //    }

    $xoops->registry()->set('TAGS_SIZE', Request::getString('size', '', 'GET'));
    $matrixPointSize = ($xoops->registry()->offsetExists('TAGS_SIZE')) ? $xoops->registry()->get('TAGS_SIZE') : $matrixPointSize;
}
if ('' != $url) {
    $qrcode = new \Xoops_qrcode();
    $qrcode->setLevel((int)($CorrectionLevel));
    $qrcode->setSize((int)($matrixPointSize));
    $qrcode->setMargin((int)($whiteMargin));
    $qrcode->setBackground(constant(mb_strtoupper('_' . $backgroundColor)));
    $qrcode->setForeground(constant(mb_strtoupper('_' . $foregroundColor)));
    $qrcode->render($url);
} else {
    $contents = '';
    $size = getimagesize($xoops->url('/images/blank.gif'));
    $handle = fopen($xoops->url('/images/blank.gif'), 'rb');
    while (!feof($handle)) {
        $contents .= fread($handle, 1024);
    }
    fclose($handle);
    echo $contents;
}
