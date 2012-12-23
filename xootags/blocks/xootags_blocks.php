<?php
/**
 * xootags module
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
 * @package         xootags
 * @since           2.6.0
 * @author          Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

function xootags_show($options)
{    $xoops = Xoops::getInstance();
    $xoops->theme()->addStylesheet('modules/xootags/css/blocks.css');

    $tags_module = Xootags::getInstance();
    $tags_config = $tags_module->LoadConfig();
    $tags_link_handler = $tags_module->LinkHandler();
    $tags_tags_handler = $tags_module->TagsHandler();

    $module_Handler = $xoops->getHandlerModule();

    $criteria = new CriteriaCompo();
    $criteria->setLimit($options[0]);
    $criteria->setGroupby('tag_term');

    if (isset($options[3])) {        $criteria->setSort( 'tag_' . $options[3] );
    } else {        $criteria->setSort( 'tag_count' );
    }
    if (isset($options[4])) {        $criteria->setOrder( $options[4] );
    } else {
        $criteria->setOrder( 'desc' );
    }

    $tags = $tags_link_handler->getTags($criteria);

    $tags_max = 1;
    $tags_min = 1;
    foreach( $tags as $k => $tag) {
        $tags_max = ($tag['tag_count'] > $tags_max) ? $tag['tag_count'] : $tags_max;
        $tags_min = ($tag['tag_count'] < $tags_min) ? $tag['tag_count'] : $tags_min;
    }

    // font size
    $font_min = $options[1];
    $font_max = $options[2];
    $tags_interval = $tags_max - $tags_min;
    $font_ratio = ($tags_interval) ? ($font_max - $font_min) / $tags_interval : 1;
    foreach( $tags as $k => $tag) {
        $tags[$k]['font'] = empty($tags_interval) ? 100 : floor( ($tag['tag_count'] - $tags_min) * $font_ratio ) + $font_min;
        $tags[$k]['size'] = (floor( ($tag['tag_count'] - $tags_min) * $font_ratio ) + $font_min) / 10;
    }

    $block['lineheight'] = $options[2];
    $block['colors'] = implode(',', $tags_config['xootags_colors']);
    $block['tags'] = $tags;
	return $block;
}

function xootags_cloud_edit($options)
{
    $tags_module = Xootags::getInstance();

    $block_form = new XoopsFormElementTray('&nbsp;', '<br />');
    // limit per page
    $block_form->addElement( new XoopsFormText(_XOO_CONFIG_LIMIT_MAIN, 'options[0]', 1, 10, $options[0] ) );
    // font size
    $block_form->addElement( new XoopsFormText(_XOO_CONFIG_FONT_MIN, 'options[1]', 1, 10, $options[1]) );
    $block_form->addElement( new XoopsFormText(_XOO_CONFIG_FONT_MAX, 'options[2]', 1, 10, $options[2]) );

    return $block_form->render();
}

function xootags_top_edit($options)
{
    $tags_module = Xootags::getInstance();

    $block_form = new XoopsFormElementTray('&nbsp;', '<br />');
    // limit per page
    $block_form->addElement( new XoopsFormText(_XOO_CONFIG_LIMIT_MAIN, 'options[0]', 1, 10, $options[0] ) );
    // font size
    $block_form->addElement( new XoopsFormText(_XOO_CONFIG_FONT_MIN, 'options[1]', 1, 10, $options[1]) );
    $block_form->addElement( new XoopsFormText(_XOO_CONFIG_FONT_MAX, 'options[2]', 1, 10, $options[2]) );

    $sort_mode = new XoopsFormSelect(_MB_XOO_TAGS_SORT . ' : ', 'options[3]', $options[3]);
    $sort_mode->addOption('term',  _MB_XOO_TAGS_SORT_ALPHABETIC);
    $sort_mode->addOption('time',  _MB_XOO_TAGS_SORT_RECENTS);
    $sort_mode->addOption('count', _MB_XOO_TAGS_SORT_HITS);

    $sort_mode->addOption('random',    _MB_XOO_TAGS_SORT_RANDOM);
    $block_form->addElement($sort_mode);

    $order_mode = new XoopsFormSelect(_MB_XOO_TAGS_ORDER . ' : ', 'options[4]', $options[4]);
    $order_mode->addOption('asc',  _MB_XOO_TAGS_ORDER_ASC);
    $order_mode->addOption('desc', _MB_XOO_TAGS_ORDER_DESC);
    $block_form->addElement($order_mode);

    return $block_form->render();
}
?>