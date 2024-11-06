/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    '../../model/shipping-rates-validator/deliverylatam',
    '../../model/shipping-rates-validation-rules/deliverylatam'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    deliveryLatamShippingRatesValidator,
    deliveryLatamShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('deliverylatam', deliveryLatamShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('deliverylatam', deliveryLatamShippingRatesValidationRules);

    return Component;
});
