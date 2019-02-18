<?php

namespace XoopsModules\Xootags;

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
 * Class XoopsFormTags
 */
class XoopsFormTags extends \Xoops\Form\Element
{
    /**
     * number of columns
     *
     * @var int
     * @access private
     */
    private $cols;

    /**
     * number of rows
     *
     * @var int
     * @access private
     */
    private $rows;

    /**
     * placeholder for this element
     *
     * @var string
     * @access private
     */
    private $placeholder;

    /**
     * Constructor
     *
     * @param string $caption     caption
     * @param string $name        name
     * @param string $value       initial content
     * @param int    $rows        number of rows
     * @param int    $cols        number of columns
     * @param string $placeholder placeholder for this element.
     */
    public function __construct($caption, $name, $value = '', $rows = 5, $cols = 5, $placeholder = '')
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->rows = (int)($rows);
        $this->cols = (int)($cols);
        $this->setValue($value);
        $this->placeholder = $placeholder;
        $this->setClass('tags');
    }

    /**
     * get number of rows
     *
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get number of columns
     *
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * getMaxcols - get the maximum columns for a field
     *
     * @return int
     */
    public function getMaxcols()
    {
        return $this->maxcols;
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        if (empty($this->placeholder)) {
            return '';
        }

        return $this->placeholder;
    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $xoops = \Xoops::getInstance();
        $xoops->theme()->addScript('modules/xootags/assets/js/jquery/jquery.tagsinput/jquery.tagsinput.js');
        $xoops->theme()->addStylesheet('modules/xootags/assets/js/jquery/jquery.tagsinput/jquery.tagsinput.css');
        $xoops->theme()->addStylesheet('media/jquery/ui/base/ui.all.css');

        $name  = $this->getName();
        $class = ('' != $this->getClass() ? " class='" . $this->getClass() . "'" : '');
        if ($this->getCols() > $this->getMaxcols()) {
            $maxcols = 5;
        } else {
            $maxcols = $this->getCols();
        }
        $class = ('' != $this->getClass() ? " class='span" . $maxcols . ' ' . $this->getClass() . "'" : " class='span" . $maxcols . "'");
        $placeholder = ('' != $this->getPlaceholder() ? " placeholder='" . $this->getPlaceholder() . "'" : '');
        $extra = ('' != $this->getExtra() ? ' ' . $this->getExtra() : '');
        $required    = ($this->isRequired() ? ' required' : '');

        $script
            = "<script type=\"text/javascript\">
        $(function () {
            $('#" . $name . "').tagsInput({
                width:'auto',
                autocomplete_url:'" . \XoopsBaseConfig::get('url')  . "/modules/xootags/include/jquery.php'
            });
        });
        </script>";

        return $script . "<textarea name='" . $name . "' title='" . $this->getTitle() . "' id='" . $name . "'" . $class . " rows='" . $this->getRows() . "'" . $placeholder . $extra . $required . '>'
        . $this->getValue() . '</textarea>';
    }
}
