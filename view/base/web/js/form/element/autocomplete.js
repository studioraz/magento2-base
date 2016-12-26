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
    'Magento_Ui/js/form/element/abstract'
], function ($, utils, Abstract) {
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

            elementTmpl: 'SR_Base/form/element/autocomplete',

            listens: {
            }
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


        getSource : function (request, response) {

            var term = request.term;

            if (utils.isEmpty(term)) {
                return;
            }

            var o = this.options;

            if (this._isDebug()) {
                o.source = this._getMockData();
            }

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

        _getMockData: function () {

            return [
                { label: "ActionScript", value: 1 },
                { label: "AppleScript", value: 2 },
                { label: "Asp", value: 3 },
                { label: "BASIC", value: 4 },
                { label: "C", value: 5 },
                { label: "C++", value: 6 },
                { label: "Clojure", value: 7 },
                { label: "COBOL", value: 8 },
                { label: "ColdFusion", value: 9 },
                { label: "Erlang", value: 10 },
                { label: "Fortran", value: 11 },
                { label: "Groovy", value: 12 },
                { label: "Haskell", value: 13 },
                { label: "Java", value: 14 },
                { label: "JavaScript", value: 15 },
                { label: "Lisp", value: 16 },
                { label: "Perl", value: 17 },
                { label: "PHP", value: 18 },
                { label: "Python", value: 19 },
                { label: "Ruby", value: 20 },
                { label: "Scala", value: 21 },
                { label: "Scheme", value: 22 }
            ];
        }
    });
});
