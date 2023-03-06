# Mage2 Module AnassTouatiCoder Base

    ``anasstouaticoder/magento2-module-base``

- [Main Functionalities](#markdown-header-main-functionalities)
- [Installation](#markdown-header-installation)
- [Configuration](#markdown-header-configuration)
- [Specifications](#markdown-header-specifications)
- [Attributes](#markdown-header-attributes)


## Main Functionalities

Base Module

## Installation
\* = in production please use the `--keep-generated` option

### install from composer 2
- basically this module will be installed as dependency for other modules also it can be Installed manually. 
- In magento project root directory run command `composer require anasstouaticoder/magento2-module-base`
- Enable the module by running `php bin/magento module:enable AnassTouatiCoder_Base`
- Flush the cache by running `php bin/magento cache:flush`


### Zip file

- Unzip the zip file in `app/code/AnassTouatiCoder`
- Enable the module by running `php bin/magento module:enable AnassTouatiCoder_Base`
- Flush the cache by running `php bin/magento cache:flush`

### In Back Office

Go to Store => Configuration, you will see that a new section `Atouati Tools` has been added. 

## Specifications

The base module will contain All common components used by other modules

## License

[MIT](https://opensource.org/licenses/MIT)
