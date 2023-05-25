<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Block\Adminhtml\Form;

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class FieldArray extends AbstractFieldArray
{
    /**
     * @var string|null
     */
    protected $dataConfig = null;
    /*
     * Additional buttons
     */
    public const ADDITIONAL_BUTTON_LIST = [
        'excluded_domain_import_button' => [// to be fixed
            'label' => 'Import',
            'class' => 'action-default scalable',
            'data_attribute' => [
                'mage-init' => [
                    'AnassTouatiCoder_Base/js/import-button' => [
                        'url' => 'atouatibase/index/import',
                        'content' => '<p>All existing items in the list will be replaced with csv file content.</p>'
                    ]
                ]
            ]
        ],
        'excluded_domain_export_button' => [
            'label' => 'Export',
            'class' => 'action-default scalable secondary',
            'data_attribute' => [
                'mage-init' => [
                    'AnassTouatiCoder_Base/js/export-button' => [
                        'url' => 'atouatibase/index/export',
                    ]
                ]
            ]
        ]
    ];

    public function __construct(
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * Add import export buttons
     * @return string
     * @throws Exception
     */
    protected function _toHtml()
    {
        foreach (self::ADDITIONAL_BUTTON_LIST as $buttonAlias => $buttonData) {
            $this->getGenerateComponentData($buttonData);
            $this->setChild(
                $buttonAlias,
                $this->getLayout()->createBlock(Button::class)->setData($buttonData)
            );
        }
        return parent::_toHtml() . $this->getChildHtml();
    }

    /**
     * Generate dynamic data for the component
     *
     * @param array $element
     * @return void
     */
    protected function getGenerateComponentData(array &$element): void
    {
        $jsComponentName = array_key_first($element['data_attribute']['mage-init']);
        $element['data_attribute']['mage-init'][$jsComponentName]['url'] =
            $this->getUrl($element['data_attribute']['mage-init'][$jsComponentName]['url']);

        $element['data_attribute']['mage-init'][$jsComponentName]['dataConfig'] = $this->getDataConfig();
        $element['data_attribute']['mage-init'][$jsComponentName]['webSiteId'] =
            $this->getRequest()->getParam('website');
        $element['data_attribute']['mage-init'][$jsComponentName]['storeId'] =
            $this->getRequest()->getParam('store');
        $element['data_attribute']['mage-init'][$jsComponentName]['formKey'] = $this->getFormKey();
    }

    /**
     * @return string
     */
    protected function getDataConfig(): string
    {
        if ($this->dataConfig !== null) {
            $pattern = "/groups\[([^]]+)\]\[groups\]\[([^]]+)\]\[fields\]\[([^]]+)\]\[value\]/";
            preg_match($pattern, $this->getElement()->getName(), $matches);
            array_shift($matches);
            $this->dataConfig = implode('_', $matches ?? []);
        }

        return $this->dataConfig;
    }

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $configData = $this->_scopeConfig->getValue($this->dataConfig);
        foreach ($configData['field_list'] as $element) {
            $this->addColumn($element['name'], [
                'label' => __($element['label']),
                'type' => $element['type'],
                'class' => ($element['required'] ? 'required-entry' : '') . ($element['class'] ?? '')
            ]);
        }

        $this->_addAfter = $configData['add_after'] ?? 0;
        $this->_addButtonLabel = __('Add New %1', $configData['field_name'] ?? '');
    }
}

