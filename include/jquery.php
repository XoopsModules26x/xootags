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

include dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

\XoopsLoad::load('system', 'system');
$system = System::getInstance();

$xoops = \Xoops::getInstance();
$xoops->disableErrorReporting();

$tag_term = Request::getString('term', ''); //$system->cleanVars($_REQUEST, 'term', '', 'string');

$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('tag_status', 1));
if ('' != $tag_term) {
    $criteria->add(new \Criteria('tag_term', '%' . $tag_term . '%', 'LIKE'));
}
$criteria->setSort('tag_count');
$criteria->setOrder('DESC');

$helper = \XoopsModules\Xootags\Helper::getInstance();
$tagsTagsHandler = $helper->tagsHandler();

$tags = $tagsTagsHandler->getObjects($criteria, true, false);

$ret = [];
if (count($tags) >= 0) {
    foreach ($tags as $k => $tag) {
        $ret[$k]['id'] = $tag['tag_term'];
        $ret[$k]['label'] = $tag['tag_term'];
        $ret[$k]['value'] = $tag['tag_term'];
    }
}
echo json_encode($ret);
