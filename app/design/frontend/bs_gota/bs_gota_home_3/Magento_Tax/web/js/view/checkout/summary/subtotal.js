
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils'
], function (Component, quote, priceUtils) {
    'use strict';

    var displaySubtotalMode = window.checkoutConfig.reviewTotalsDisplayMode;

    return Component.extend({
        defaults: {
            displaySubtotalMode: displaySubtotalMode,
            template: 'Magento_Tax/checkout/summary/subtotal'
        },
        totals: quote.getTotals(),

        /**
         * @return {*|String}
         */
        getValue: function () {
            var price = 0;

            if (this.totals()) {
                price = this.totals().subtotal;
            }

            return this.formatPrice(price);
        },

        /**
         * @return {Boolean}
         */
        isBothPricesDisplayed: function () {
            return this.displaySubtotalMode === 'both'; //eslint-disable-line eqeqeq
        },

        /**
         * @return {Boolean}
         */
        isIncludingTaxDisplayed: function () {
            return this.displaySubtotalMode === 'including'; //eslint-disable-line eqeqeq
        },

        /**
         * @return {*|String}
         */
        getValueInclTax: function () {
            var price = 0;

            if (this.totals()) {
                price = this.totals()['subtotal_incl_tax'];
            }

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