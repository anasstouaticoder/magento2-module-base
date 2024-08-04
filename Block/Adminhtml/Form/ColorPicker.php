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

class ColorPicker extends Field
{
    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setData('extra_params', 'style="width:50px;" data-hex="true"');
        $html = parent::_getElementHtml($element);
        $html .= <<<HTML
            <script>
                require(['jquery', 'jquery/colorpicker/js/colorpicker'], function ($) {
                    $(document).ready(function () {
                        $('#{$element->getHtmlId()}').css('backgroundColor', $('#{$element->getHtmlId()}').val());
                        $('#{$element->getHtmlId()}').ColorPicker({
                            color: $('#{$element->getHtmlId()}').val(),
                            onChange: function (hsb, hex, rgb) {
                                $('#{$element->getHtmlId()}').val('#' + hex);
                                $('#{$element->getHtmlId()}').css('backgroundColor', '#' + hex);
                            }
                        });
                    });
                });
            </script>
HTML;

        return $html;
    }
}
