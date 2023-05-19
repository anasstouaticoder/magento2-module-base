<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Controller\Adminhtml\Index;

use AnassTouatiCoder\Base\Model\Config;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\File\Csv;

abstract class DataMigration implements HttpPostActionInterface
{
    /**
     * @var int
     */
    protected $websiteId;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var array
     */
    protected $CSVHeader;

    /**
     * @var Csv
     */
    protected $csv;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $CSVdata;

    /**
     * @var string
     */
    protected $xmlPath;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @param HttpRequest $request
     * @param FileFactory $fileFactory
     * @param Csv $csv
     * @param Config $config
     */
    public function __construct(
        HttpRequest   $request,
        FileFactory   $fileFactory,
        Csv           $csv,
        Config        $config
    ) {
        $this->request = $request;
        $this->fileFactory = $fileFactory;
        $this->csv = $csv;
        $this->config = $config;
        $this->initScopeIds();
        $this->initDataConfig();
    }

    /**
     * @return void
     */
    protected function initScopeIds(): void
    {
        $this->websiteId = (int)$this->request->getParam('website_id');
        $this->storeId = (int)$this->request->getParam('store_id');
    }

    /**
     * @return string|null
     */
    protected function getDataConfig(): ?string
    {
        return $this->request->getParam('data_config');
    }

    /**
     * @return void
     */
    protected function initDataConfig(): void
    {
        $dataConfig = $this->config->getConfigData($this->getDataConfig());
        $this->xmlPath = $dataConfig['table_config'];
        foreach ($dataConfig['field_list'] as $element) {
            $this->CSVHeader[] = $element['label'];
            $this->fields[$element['name']] = $element['type'];
        }

        $this->CSVdata = $this->config->getCSVContent(
            $this->xmlPath ?? '',
            $this->websiteId,
            $this->storeId
        );
    }
}
