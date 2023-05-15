<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Block\Adminhtml\Form;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Multiselect;
use Magento\Framework\Escaper;
use Magento\Framework\Math\Random;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class MultiSelectChooser extends Multiselect
{
    /**
     * @var Json
     */
    private $json;

    /**
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        Factory           $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper           $escaper,
        Json              $json,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null,
        ?Random $random = null
    ) {
        $this->json = $json;

        parent::__construct($factoryElement, $factoryCollection, $escaper, $data,$secureRenderer, $random);
        $this->setNoWrapAsAddon(true);
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        $this->addClass('admin__field-control admin__control-grouped');
        $html = <<<HTML
<div id="{$this->getHtmlId()}" {$this->serialize($this->getHtmlAttributes())}  {$this->_getUiId()}
data-bind="scope:'{$this->getOriginalName()}'" data-index="index">
<!-- ko foreach: elems() -->
<input type="hidden" name="{$this->getOriginalName()}" data-bind="value: value" ></input>
<!-- ko template: elementTmpl --><!-- /ko -->
<!-- /ko -->

</div>
{$this->getAfterElementHtml()}
HTML;

        return $html;
    }

    /**
     * get Original name without []
     * @return mixed
     */
    protected function getOriginalName()
    {
        return AbstractElement::getName();
    }

    /**
     * get Original name without []
     * @return mixed
     */
    public function getName()
    {
        return AbstractElement::getName();
    }

    /**
     * @return string
     */
    public function getAfterElementHtml()
    {
        $items = $this->json->serialize($this->getItems());
        $values = $this->json->serialize($this->getValues());
        $html = <<<HTML
<script type="text/x-magento-init">
{
"*": {
"Magento_Ui/js/core/app": {
"components": {
"{$this->getOriginalName()}": {
"component": "uiComponent",
"children": {
"select_{$this->getOriginalName()}": {
"component": "AnassTouatiCoder_Base/js/multiselect-chooser",
"config": {
"filterOptions": true,
"disableLabel": true,
"chipsEnabled": true,
"levelsVisibility": "1",
"elementTmpl": "ui/grid/filters/elements/ui-select",
"targetElementName": "{$this->getOriginalName()}",
"options": {$items},
"value": {$values},
"listens": {
"index=create_category:responseData": "setParsed",
"newOption": "toggleOptionSelected"
},
"config": {
"dataScope": "select_{$this->getOriginalName()}",
"sortOrder": 10
}
}
}
}
}
}
}
}
}
</script>
HTML;
        return $html;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $sourceArray = [];
        foreach ($this->getData('values') as $value) {
            $sourceArray[] = [
                'is_active' => 1,
                'label' => $value['label'],
                'value' => $value['value'],
                "__disableTmpl" => 0,
            ];
        }

        return $sourceArray;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = $this->getValue();
        if (!is_array($values) && !is_null($values)) {
            $values = explode(',', $values);
        }
        if (empty($values)) {
            return [];
        }

        return $values;
    }
}
