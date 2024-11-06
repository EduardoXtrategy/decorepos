define([
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils'
], function (Component, quote, priceUtils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Uzer_CreditTerms/payment/credit-term-form'
        },
        getValueAvailable: function () {
            return window.checkoutConfig.payment.terms.available;
        },

        getAvailableValueFormat: function () {
            return this.formatPrice(this.getValueAvailable());
        },

        isAmountComplete: function () {
            let totals = quote.getTotals()();
            let grandTotal = (totals ? totals : quote)['grand_total'];
            return grandTotal <= this.getValueAvailable();
        },
        /**
         * @param {*} price
         * @return {*|String}
         */
        getFormattedPrice: function (price) {
            //todo add format data
            return this.formatPrice(price);
        },
        formatPrice: function (price) {
            let previousFormat = window.checkoutConfig.priceFormat;
            previousFormat.precision = 2;
            previousFormat.requiredPrecision = 2;
            return priceUtils.formatPrice(price, previousFormat);
        }
    });
});
