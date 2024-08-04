<?php
/**
 * Copyright (c) 2024
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
declare(strict_types=1);

namespace AnassTouatiCoder\Base\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File;

class SVGImage extends File
{
    protected function _getAllowedExtensions()
    {
        return ['svg'];
    }

    public function beforeSave()
    {
        $value = $this->getValue();

        if (is_array($value) && !empty($value['delete'])) {
            $this->setValue('');
            return $this;
        }
        if ($value['name'] === '') {
            $this->unsValue();
            return $this;
        }
        try {
            $file = $this->getFileData();
            if (isset($file['tmp_name'])) {
                $this->validateSvgFile($file);
                $fileContents = file_get_contents($file['tmp_name']);
                $this->setValue($fileContents);
            }
        } catch (\Exception $e) {
            throw new \Exception(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
        return $this;
    }


    /**
     * @param array $fileData
     * @return void
     * @throws \Exception
     */
    protected function validateSvgFile(array $fileData)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($fileData['tmp_name']);
        if ($mimeType !== 'image/svg+xml') {
            throw new \Exception(sprintf('Only SVG files are allowed for config field %s', $this->getPath()));
        }
    }

}
