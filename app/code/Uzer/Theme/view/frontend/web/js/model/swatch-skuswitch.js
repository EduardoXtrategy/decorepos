define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';
    return function (targetModule) {
        let updatePrice = targetModule.prototype._UpdatePrice;
        targetModule.prototype.configurableSku = $('div.product-info-main .sku .value').html();
        targetModule.prototype._UpdatePrice = wrapper.wrap(updatePrice, function (original) {
            let allSelected = true;
            for (let i = 0; i < this.options.jsonConfig.attributes.length; i++) {
                if (!$('div.product-info-main .product-options-wrapper .swatch-attribute.' + this.options.jsonConfig.attributes[i].code).attr('option-selected')) {
                    allSelected = false;
                }
            }
            let simpleSku = this.configurableSku;
            if (allSelected) {
                let products = this._CalcProducts();
                let simple = this.options.jsonConfig.skus[products.slice().shift()];
                if (simple) {
                    simpleSku = simple['sku'];
                }
            }
            $('div.product-info-main .sku .value').html(simpleSku);
            return original();
        });
        return targetModule;
    };
});
