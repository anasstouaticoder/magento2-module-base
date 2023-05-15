/**
 * Copyright (c) 2023
 * MIT License
 * Module AnassTouatiCoder_Base
 * Author Anass TOUATI anass1touati@gmail.com
 */
define([
    'underscore',
    'Magento_Catalog/js/components/new-category'
], function (_, Category) {
    'use strict';

    function flattenCollection(array, separator, created) {

        var i = 0,
            length,
            childCollection;
        array = _.compact(array);
        length = array.length;
        created = created || [];
        for (i; i < length; i++) {
            created.push(array[i]);
            if (array[i].hasOwnProperty(separator)) {
                childCollection = array[i][separator];
                delete array[i][separator];
                flattenCollection.call(this, childCollection, separator, created);
            }
        }
        return created;
    }
    return Category.extend({
        targetElementName: '',
        /**
         * Set option to options array.
         *
         * @param {Object} option
         * @param {Array} options
         */
        setOption: function (option, options) {

            var parent = parseInt(option.parent);
            if (_.contains([0, 1], parent)) {
                options = options || this.cacheOptions.tree;
                options.push(option);
                var copyOptionsTree = JSON.parse(JSON.stringify(this.cacheOptions.tree));
                this.cacheOptions.plain = flattenCollection(copyOptionsTree, this.separator);
                this.options(this.cacheOptions.tree);
            } else {
                this._super(option, options);
            }
        },
        initConfig: function (config) {
            this.targetElementName = config.targetElementName;
            this._super();
        }
    });
});
