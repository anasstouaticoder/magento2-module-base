<h1 style="text-align: center;">Magento 2 Module AnassTouatiCoder Base</h1>
<div style="text-align: center;">
  <p>Copy field path and value, display its override values in parent scope</p>
  <img src="https://img.shields.io/badge/magento-2.2%20|%202.3%20|%202.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/anasstouaticoder/magento2-module-base" target="_blank"><img src="https://img.shields.io/packagist/v/anasstouaticoder/magento2-module-base.svg?style=flat-square" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/anasstouaticoder/magento2-module-base" target="_blank"><img src="https://poser.pugx.org/anasstouaticoder/magento2-module-base/downloads" alt="Composer Downloads" /></a>
  <a href="https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg?style=flat-square" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

    ``anasstouaticoder/magento2-module-base``

- [Main Functionalities](#markdown-header-main-functionalities)
- [Installation](#markdown-header-installation)
- [Specifications](#markdown-header-specifications)
- [License](#markdown-header-License)


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
