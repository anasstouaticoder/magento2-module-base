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
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Message\ManagerInterface;

class Import extends DataMigration
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @param HttpRequest $request
     * @param FileFactory $fileFactory
     * @param Csv $csv
     * @param Config $config
     * @param JsonFactory $jsonFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        HttpRequest      $request,
        FileFactory      $fileFactory,
        Csv              $csv,
        Config           $config,
        JsonFactory      $jsonFactory,
        ManagerInterface $messageManager
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->messageManager = $messageManager;

        parent::__construct(
            $request,
            $fileFactory,
            $csv,
            $config
        );
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        if (!$this->request->isPost()) {
            $message = __('No file uploaded');
            $this->messageManager->addErrorMessage($message);
        } else {
            $fileContainer = $this->request->getFiles('import_file');
            if (isset($fileContainer['tmp_name'])) {
                try {
                    // Get uploaded file
                    $file = $fileContainer['tmp_name'];
                    $importData = $this->csv->getData($file);

                    if (!empty($importData) && isset($importData[0][0]) && $importData[0][0] === $this->CSVHeader[0]) {
                        array_shift($importData);
                    }

                    $columnCount =  count($this->CSVHeader);
                    if ($columnCount === 0) {
                        $message = __('Please Make sure that the CSV file is not empty');
                        $this->messageManager->addErrorMessage($message);
                    } else {
                        $dataToImport = $this->prepareDataToImport($importData);
                        if (count($dataToImport) > 1) {
                            $this->config->saveCSVContent($this->xmlPath, $dataToImport, $this->websiteId, $this->storeId);
                            $message = __('Items have been imported.');
                            $this->messageManager->addSuccessMessage($message);
                        } else {
                            $message = __('No valid items were found');
                            $this->messageManager->addErrorMessage($message);
                        }
                    }
                } catch (LocalizedException|\Exception $e) {
                    $message = __('An error occurred');
                    $this->messageManager->addErrorMessage(__('An error occurred while importing CSV file: %1', $e->getMessage()));
                    $this->messageManager->addErrorMessage(__('$e' . $e->getRawMessage()));
                }
            } else {
                $this->messageManager->addErrorMessage(__('Please upload a file and submit'));
            }
        }
        return $result->setData([$message]);
    }

    /**
     * @param $importData
     * @return array
     */
    protected function prepareDataToImport($importData): array
    {
        $dataToImport = [];
        foreach ($importData as $data) {
            if (count($data) === count($this->fields)) {
                $row = [];
                $iterator = 0;
                foreach ($this->fields as $field => $fieldType) {
                    switch ($fieldType) {
                        case 'select':
                            $row[$field] = [$data[$iterator]];
                            break;
                        default:
                            $row[$field] = $data[$iterator];
                            break;
                    }
                    $iterator++;
                }
                $dataToImport[uniqid('_')] = $row;
            }
        }

        return $dataToImport;
    }
}
