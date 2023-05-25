/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'mage/translate',
], function ($, alert) {
    'use strict';

    return function (config, element) {

        $(element).click(function () {
            $.ajax({
                url: config.url,
                type: 'POST',
                data: {
                    form_key: window.FORM_KEY,
                    store_id: config.storeId,
                    website_id: config.webSiteId,
                    data_config: config.dataConfig
                },
                success: function (data) {
                    // Create a hidden anchor tag with the download link
                    let link = document.createElement('a');
                    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(data));
                    link.setAttribute('download', dataConfig + '.csv');
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    // Remove the anchor tag from the document
                    document.body.removeChild(link);
                },
                error: function (xhr, status, error) {
                    let errorMessage = $.mage.__('An error occurred: ') + error;
                    if (xhr.status == 401) {
                        errorMessage = $.mage.__('You are no longer logged in! Please refresh the page');
                    }
                    alert({
                        title: $.mage.__('Error'),
                        content: errorMessage
                    });
                }
            });
        });
    };
});
