<?php

/*
 * Copyright (c) 2024
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */

declare(strict_types=1);

namespace AnassTouatiCoder\Base\Block\Adminhtml\Form;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;

class ImageSize extends Field
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $value = $element->getValue();
        $width = $value['width'] ?? '';
        $height = $value['height'] ?? '';
        // TODO use template phtml
        $html = '<div style="display: flex; gap: 10px;">';
        $html .= '<input type="number" name="' . $element->getName() . '[width]" value="' . $width . '" placeholder="Width" style="width:100px" />';
        $html .= '<input type="number" name="' . $element->getName() . '[height]" value="' . $height . '" placeholder="Height" style="width:100px" />';
        $html .= '</div>';

        return $html;
    }
}
