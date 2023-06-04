<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_SitemapRemoveItem
 * Author Anass TOUATI anass1touati@gmail.com
 */

declare(strict_types=1);

namespace AnassTouatiCoder\Base\Block\Adminhtml\Form;

use Magento\Framework\View\Element\Html\Select;

class GenericRenderer extends Select
{
    /**
     * Add select options
     *
     * @return array
     */
    public function addOptions(): array
    {
        $optionList = $this->getOptionArrayDta();

        return $optionList;
    }

    /**
     * Set inputName to apply it in request params
     *
     * @param string $value
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value . '[]');
    }

    /**
     * {@inheritdoc }
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->addOptions());
        }

        return parent::_toHtml();
    }

    /**
     * @return array
     */
    protected function getOptionArrayDta() :array
    {
        // To do remove ObjectManager Logic here
        $toOptionArray = \Magento\Framework\App\ObjectManager::getInstance()
            ->get($this->getOptionArrayClass())->toOptionArray();

        return $toOptionArray;
    }
}
