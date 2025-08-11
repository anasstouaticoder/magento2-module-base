<?php
/*
 * Copyright (c) 2024
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */

declare(strict_types=1);

namespace AnassTouatiCoder\Base\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json as MagentoJson;

class Json extends Value
{
    /**
     * @var MagentoJson
     */
    protected $json;

    public function __construct(
        Context              $context,
        Registry             $registry,
        ScopeConfigInterface $config,
        TypeListInterface    $cacheTypeList,
        MagentoJson          $json,
        ?AbstractResource     $resource = null,
        ?AbstractDb           $resourceCollection = null,
        array                $data = []
    ) {
        $this->json = $json;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Before save, encode data to JSON
     *
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();

        if (is_array($value)) {
            $json = $this->json->serialize($value);
            if ($json === false) {
                throw new LocalizedException(__('Unable to encode configuration data to JSON format.'));
            }
            $this->setValue($json);
        }

        return parent::beforeSave();
    }

    /**
     * After load, decode data from JSON
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();

        if ($value) {
            $decodedValue = $this->json->unserialize($value);
            $this->setValue($decodedValue);
        }

        return parent::_afterLoad();
    }
}
