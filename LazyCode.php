<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
namespace AnassTouatiCoder\Base;

use Magento\Framework\App\ObjectManager;
use Psr\Log\LoggerInterface;

/**
 * The Code Class brings back old school ways to use common classes
 * back then when Magento used to be fun => ex : Mage::log()
 * Please use it for development purpose
 */
class Code
{
    /**
     * @var LoggerInterface
     */
    private static $logger;

    /**
     * Init logger
     *
     * @return void
     */
    private static function initLogger()
    {
        if (self::$logger === null) {
            self::$logger = ObjectManager::getInstance()->get(LoggerInterface::class);
        }
    }

    /**
     * @return LoggerInterface
     */
    public static function getLog()
    {
        self::initLogger();
        return self::$logger;
    }
}
