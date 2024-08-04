<?php
/**
 * Copyright (c) 2024
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Block\Adminhtml\Form\Element;

use Magento\Framework\Data\Form\Element\File;

class SVGImage extends File
{

    /**
     * Get element html
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = parent::getElementHtml();
        // todo problem when we save without changing the svg
        $html .= $this->_getDeleteCheckbox();
        return $html;
    }
    /**
     * Get html for additional delete checkbox field
     *
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ((string)$this->getValue()) {
            $label = __('Delete File');
            $html .= '<br/><input type="checkbox" name="' .
                parent::getName() .
                '[delete]" value="1" class="checkbox" id="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' disabled="disabled"' : '') .
                '/>';
            $html .= '<label for="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' class="disabled"' : '') .
                '> ' .
                $label .
                '</label>';
            $html .= '</div>';
        }
        return $html;
    }

}
