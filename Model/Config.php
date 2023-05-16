<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
namespace AnassTouatiCoder\Base\Model;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

class Config
{
    public const SCOPE_TYPE_WEBSITES = 'websites';

    public const SCOPE_TYPE_STORES = 'stores';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var string
     */
    protected $xmlPath;

    /**
     * @var ConfigInterface
     */
    private $configResource;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ConfigInterface $configResource
     * @param Json|null $serializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ConfigInterface $configResource,
        Json $serializer = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configResource = $configResource;
        // For Magento Version < 2.1.17 class Json doesn't exist
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * @param $nodeName
     * @return array
     */
    public function getConfigData($nodeName)
    {
        return $this->scopeConfig->getValue($nodeName);
    }

    /**
     * @param int $websiteId
     * @param int $storeId
     * @return mixed
     */
    public function getCSVContent(string $xmlPath, int $websiteId, int $storeId)
    {
        $this->xmlPath = $xmlPath;
        $value = '';
        if ($websiteId) {
            $value = $this->getConfigValue(
                self::SCOPE_TYPE_WEBSITES,
                $websiteId
            );
        } elseif ($storeId) {
            $value = $this->getConfigValue(
                self::SCOPE_TYPE_STORES,
                $storeId
            );
        } else {
            $value = $this->getConfigValue();
        }

        return $value;
    }

    /**
     * @param string $scope
     * @param $scopeCode
     * @return mixed
     */
    protected function getConfigValue(string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        $value = $this->scopeConfig->getValue($this->xmlPath, $scope, $scopeCode);
        return json_decode($value, true);
    }

    /**
     * @param array $data
     * @param int $websiteId
     * @param int $storeId
     * @return void
     */
    public function saveCSVContent(string $xmlPath, array $data, int $websiteId, int $storeId)
    {
        $this->xmlPath = $xmlPath;
        if ($websiteId) {
            $this->saveConfigValue($data, self::SCOPE_TYPE_WEBSITES, $websiteId);
        } elseif ($storeId) {
            $this->saveConfigValue($data, self::SCOPE_TYPE_STORES, $storeId);
        } else {
            $this->saveConfigValue($data);
        }
        // Clear the scope config cache
        $this->scopeConfig->clean();
    }

    /**
     * @param $data
     * @param string $scope
     * @param int $scopeCode
     * @return void
     */
    protected function saveConfigValue(
        $data,
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        int $scopeCode = 0
    ): void {
        $data = $this->serializer->serialize($data);
        $this->configResource->saveConfig(
            $this->xmlPath,
            $data,
            $scope,
            $scopeCode
        );
    }
}
