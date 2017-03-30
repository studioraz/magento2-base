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

/** Creates autocomplete binding and registers in to ko.bindingHandlers object */

define([
    'ko',
    'underscore',
    'jquery',
    'mage/translate'
], function (ko, _, $, $t) {
    'use strict';

    var defaults = {
        messages: {
            noResults: '',
            results: function (amount) {
                return '';

                /* SR-TODO: add custom message + translation + below element.
                 var text = amount > 1 ? $t("results are available") : $t("result is available");
                 return amount +  ' ' + text +  ', ' + $t("use up and down arrow keys to navigate.");
                 */

            }
        }
    };

    ko.bindingHandlers.autocomplete = {
        /**
         * Initializes  jquery.ui.autocomplete widget on element and stores it's value to observable property.
         * Autocomplete binding takes either observable property or object
         *  { source: {ko.observable}, options: {Object}, select: {ko.observable} }.
         * For more info about options take a look at "mage/calendar" and jquery.ui.datepicker widget.
         * @param {HTMLElement} el - Element, that binding is applied to
         * @param {Function} valueAccessor - Function that returns value, passed to binding
         * @param {Object} allBindings - knockoutjs binding object
         * @param {Function} UiClass - Magento UI Component class
         */
        init: function (el, valueAccessor, allBindings, UiClass) {
            var config = valueAccessor(),
                options = {
                    'source': $.proxy(config.source, UiClass),
                    'select': $.proxy(config.select, UiClass),
                    'open': function (event, ui) {
                        var firstElement = $(this).data('uiAutocomplete').menu.element[0].children[0],
                            input = $(this),
                            original = input.val(),
                            firstElementText = $(firstElement).text();
                        /*
                         here we want to make sure that we're not matching something that doesn't start
                         with what was typed in
                         */
                        if (firstElementText.toLowerCase().indexOf(original.toLowerCase()) === 0) {
                            input.val(firstElementText);//change the input to the first match

                            input[0].selectionStart = original.length; //highlight from end of input
                            input[0].selectionEnd = firstElementText.length;//highlight to the end
                        }
                    }
                };


            _.extend(options, defaults);

            _.extend(options, config.options);

            $(el).autocomplete(options);

            $(el).blur();

        }
    };
});
