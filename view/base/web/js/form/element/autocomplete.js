/**
 * SR Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * https://wiki.studioraz.co.il/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@studioraz.com so we can send you a copy immediately.
 *
 * @package     SR_Base
 * @copyright   Copyright (c) 2016 SR Inc. (https://studioraz.co.il)
 * @license     https://wiki.studioraz.co.il/wiki/EULA  End-user License Agreement
 */

define([
    'jquery',
    'mageUtils',
    'Magento_Ui/js/form/element/abstract',
    'Magento_Ui/js/lib/validation/validator'
], function ($, utils, Abstract, validator) {
    'use strict';

    return Abstract.extend({
        defaults: {
            options: {
                debug : false,
                filterProperty: 'label',
                termAjaxArgument : null,
                source  : null
            },

            validationParams: {},

            template : 'ui/form/field',

            elementTmpl: 'SR_Base/form/element/autocomplete'
        },


        /** @inheritdoc */
        initialize: function () {

            this._super();
        },

        /**
         * Initializes regular properties of instance.
         *
         * @returns {Object} Chainable.
         */
        initConfig: function () {
            this._super();

            return this;
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            return this._super().observe(['getSource','selectedValue']);
        },

        selectedValue : function (event, ui) {
            var value = ui.item.value;
            if (value != this.value()) {
                this.value(ui.item.value);
            }
        },

        validate : function() {

            var result = this._super();

            if (!result.valid || !this.options.validateValue) return result;

            var value = this.value();

            var property = this.options.filterProperty;

            var result = validator('validate-value-exists', value, this.options.source.map(function(item) {
                return item[property];
            }));

            if (!result.passed) {
                this.error(result.message);
                this.source.set('params.invalid', true);
            }

            return {
                valid: result.passed,
                target: this
            };
        },


        getSource : function (request, response) {

            var term = request.term;

            if (utils.isEmpty(term)) {
                return;
            }

            var o = this.options;

            if ($.isArray(o.source)) {
                response(this.filter(o.source, term));
            } else if ($.type(o.source) === 'string') {
                if (this._xhr) {
                    this._xhr.abort();
                }


                var ajaxData = this.getAjaxData();

                this._xhr = $.ajax($.extend(true, {
                    url: o.source,
                    type: 'POST',
                    dataType: 'json',
                    data: ajaxData,
                    success: $.proxy(function (items) {
                        this.options.data = items;
                        response.apply(response, arguments);
                    }, this)
                }, o.ajaxOptions || {}));
            }

        },

        getAjaxData : function () {
            return {};
        },

        /**
         * Perform filtering in advance loaded items and returns search result
         * @param {Array} items - all available items
         * @param {String} term - search phrase
         * @return {Object}
         */
        filter: function (items, term) {
            var matcher = new RegExp(term.replace(/[\-\/\\\^$*+?.()|\[\]{}]/g, '\\$&'), 'i');
            var itemsArray = $.isArray(items) ? items : $.map(items, function (element) {
                return element;
            });
            var property = this.options.filterProperty;

            return $.grep(
                itemsArray,
                function (value) {
                    return matcher.test(value[property] || value.id || value);
                }
            );
        },


        _isDebug : function() {
            return this.options.debug;
        },

    });
});
