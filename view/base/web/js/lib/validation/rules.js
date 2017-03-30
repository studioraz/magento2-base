/*
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'underscore',
    'Magento_Ui/js/lib/validation/validator',
    'mage/translate'
], function ($, _, validator, $t) {


    var rulesList = _.mapObject({
        "validate-value-exists": [
            function (value, list) {

                if ($.isArray(list)) {
                    return _.indexOf(list, value) == -1 ? false : true;
                }

                return false;
            },
            $t('You must enter a value from the list')
        ],
        "validate-israeli-id-number": [
            function (value) {

                // Validate correct input
                if (value.length != 9) {
                    return false;
                }

                // Checks the ID algoritem.
                var mone = 0, incNum;
                for (var index = 0; index < 9; index++) {
                    incNum = Number(value.charAt(index));
                    incNum *= (index % 2) + 1;
                    if (incNum > 9)
                        incNum -= 9;
                    mone += incNum;
                }

                return mone % 10 == 0;

            },
            $t('Please enter a valid ID number')
        ],
        "validate-israeli-phone-number": [
            function (value) {
                return /^(?:(?:(?:\s|\.|-)?)|(0[23489]{1})|(0[57]{1}[0-9]))?([^0\D]{1}\d{2}(?:\s|\.|-)?\d{4})$/i.test(value)
            },
            $t('Please enter a phone number without hyphens and spaces')
        ]
    }, function (data) {
        return {
            handler: data[0],
            message: data[1]
        };
    });


    _.each(rulesList, function (rule, id) {
        validator.addRule(id, rule.handler, rule.message);
    });

})