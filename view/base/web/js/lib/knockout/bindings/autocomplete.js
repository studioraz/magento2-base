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
            results: function( amount ) {
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
                options = {};

            _.extend(options, defaults);

            _.extend(options, config.options);

            $(el).autocomplete(options);

            $(el).autocomplete('option', 'source', $.proxy(config.source, UiClass));

            $(el).autocomplete('option', 'select', $.proxy(config.select, UiClass));

            $(el).blur();

            // SR-TODO: check if knockout js event registration is needed.
            /*ko.utils.registerEventHandler(el, 'blur', function () {
                observable(this.value);
            });*/

        }
    };
});
