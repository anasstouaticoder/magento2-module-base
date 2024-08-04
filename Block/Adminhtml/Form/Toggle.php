<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Block\Adminhtml\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Toggle extends Field
{
    /**
     * Render element value as a toggle switch
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '<div class="admin__field-control toggle-switch">';
        $html .= '<div class="switch"><input type="checkbox" id="' . $element->getHtmlId() . '" name="' . $element->getName() . '" class="admin__control-checkbox" ' . $element->serialize() . '>';
        $html .= '<label class="switch-label" for="' . $element->getHtmlId() . '"></label></div>';
        $html .= '</div>';

        return $html;
    }

//    protected $_template = 'AnassTouatiCoder_Base::form/field/toggle.phtml';


}
