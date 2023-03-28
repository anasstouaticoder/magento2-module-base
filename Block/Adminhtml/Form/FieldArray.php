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
use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Exception\LocalizedException;

abstract class FieldArray extends AbstractFieldArray
{
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

    /**
     * Prepare the layout
     *
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        foreach (self::ADDITIONAL_BUTTON_LIST as $buttonAlias => $buttonData) {
            $this->getGenerateComponentData($buttonData);
            $this->setChild(
                $buttonAlias,
                $this->getLayout()->createBlock(Button::class)->setData($buttonData)
            );
        }

        return $this;
    }
    /**
     * Add import export buttons
     * @return string
     * @throws Exception
     */
    protected function _toHtml()
    {
        return parent::_toHtml() . $this->getChildHtml();
    }

    /**
     * Generate dynamic data for the component
     *
     * @param array $element
     * @return void
     */
    protected function getGenerateComponentDAta(array &$element): void
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
        return $this->dataConfig;
    }
}
