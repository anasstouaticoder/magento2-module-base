<?php
/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */

use Magento\Framework\Component\ComponentRegistrar;

const AT_CODE =  \AT\Code::class;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'AnassTouatiCoder_Base', __DIR__);
