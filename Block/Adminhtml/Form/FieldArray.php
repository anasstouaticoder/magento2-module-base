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
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class FieldArray extends AbstractFieldArray
{
    /**
     * @var string|null
     */
    protected $dataConfig = null;

    /**
     * @var array
     */
    protected array $config = [];
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
     * @var GenericRenderer|(GenericRenderer&BlockInterface)|BlockInterface
     */
    protected $genericRenderer = [];

    /**
     * @param Context $context
     * @param array $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
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
        if ($this->dataConfig === null) {
            $pattern = "/\[(.*?)\]/";
            preg_match_all($pattern, $this->getElement()->getName(), $matches);

            // Filter and concatenate the captured matches with underscores
            $filteredMatches = array_filter($matches[1], function ($match) {
                return !in_array($match, ['groups', 'fields', 'value']);
            });
            $this->dataConfig = implode('_', $filteredMatches);
        }

        return $this->dataConfig;
    }

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->config = $this->_scopeConfig->getValue($this->dataConfig);
        foreach ($this->config['field_list'] as $element) {
            $elementData = [
                'label' => __($element['label']),
                'type' => $element['type'],
                'class' => ($element['required'] ? 'required-entry' : '') . ($element['class'] ?? '')
            ];
            if ($element['type'] === 'select') {
                $elementData['renderer'] = $this->getGenericRenderer($element['option_list'], $element['name']);
            }
            $this->addColumn($element['name'], $elementData);
        }

        $this->_addAfter = $configData['add_after'] ?? 0;
        $this->_addButtonLabel = __('Add New %1', $configData['field_name'] ?? '');
    }

    protected function getGenericRenderer($optionToArrayClass, $fieldName)
    {
        if (!isset($this->genericRenderer[$fieldName])) {
            $this->genericRenderer[$fieldName] = $this->getLayout()->createBlock(
                GenericRenderer::class,
                '',
                ['data' => ['is_render_to_js_template' => true, 'option_array_class' => $optionToArrayClass]]
            )->setClass(uniqid('') . '_select admin__control-select');
        }

        return $this->genericRenderer[$fieldName];
    }

    /**
     * {@inheritDoc }
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $optionExtraAttr = [];

        foreach ($this->config['field_list'] as $element) {
            if ($element['type'] === 'select') {
                $optionExtraAttr['option_' . $this->getGenericRenderer($element['option_list'], $element['name'])
                    ->calcOptionHash($row->getData($element['name'])[0])] =
                    'selected="selected"';
            }
        }

        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}
