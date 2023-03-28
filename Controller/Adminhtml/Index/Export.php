<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultInterface;

class Export extends DataMigration
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|ResultInterface|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $fileName = 'data.csv';
        $data = $this->CSVdata;

        $csvData[] = $this->CSVHeader;
        foreach ($data as $row) {
            $rowData = [];
            foreach ($row as $column) {

                if (is_array($column)) {
                    $rowData[] =  implode(',', $column);
                } else {
                    $rowData[] = $column;
                }

            }
            if (!empty($rowData)) {
                $csvData[] = $rowData;
            }

        }

        $this->csv->appendData(DirectoryList::MEDIA . '/' . $fileName, $csvData);

        $content = [
            'type' => 'filename',
            'value' => $fileName,
            'rm' => true
        ];

        return $this->fileFactory->create($fileName, $content, DirectoryList::MEDIA);
    }
}
