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

define(function (require) {
    'use strict';
    return {
        binding : require('./knockout/bindings'),
        validation   : require('./validation/rules')
    };
});
