<?php

namespace XoopsModules\Xootags;

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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         xootags
 * @since           2.6.0
 * @author          Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author          Laurent JEN (Aka DuGris)
 */

/**
 * Class Link
 */
class Link extends \XoopsObject
{
    // constructor

    public function __construct()
    {
        $this->initVar('tl_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tag_id', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_modid', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_itemid', XOBJ_DTYPE_INT, 0);
        $this->initVar('tag_time', XOBJ_DTYPE_INT, 0);
    }

    /**
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     *
     * @return array
     */
    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        return parent::getValues();
    }
}
