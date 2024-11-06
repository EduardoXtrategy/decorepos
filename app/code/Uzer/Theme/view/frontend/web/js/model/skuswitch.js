define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';
    return function (targetModule) {
        let reloadPrice = targetModule.prototype._reloadPrice;
        let reloadPriceWrapper = wrapper.wrap(reloadPrice, function (original) {
            let result = original();
            let simple = this.options.spConfig.skus[this.simpleProduct];
            if (simple) {
                let simpleSku = simple['sku'];

                if (simpleSku !== '') {
                    $('div.product-info-main .sku .value').html(simpleSku);
                }
            }

            return result;
        });
        targetModule.prototype._reloadPrice = reloadPriceWrapper;
        return targetModule;
    };
});
