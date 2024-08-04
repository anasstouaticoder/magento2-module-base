<?php
/**
 * Copyright (c) 2024
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Block\Adminhtml\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class SVGImage extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setElement($element);
        $value = $element->getValue();
        $imageHtml = '';
        // display SVG content from the database if available
        if ($value) {
            $imageSrc = 'data:image/svg+xml;base64,' . base64_encode($value);
            $imageHtml = '<br/><img src="' . $imageSrc . '" alt="Uploaded SVG Image" width="100" height="100" />';
        }

        $html = $imageHtml !== '' ? $imageHtml : '<br/><br/><br/>';

        $html .= '<br/>' . $element->getElementHtml();

        return $html;
    }
}
