define([
    'ko',
    'Magento_Checkout/js/model/quote'
], function (ko, quote) {
    'use strict';

    return function () {
        let purchaseOrder = ko.observable('');
        console.log(purchaseOrder);
        return {
            purchaseOrder: purchaseOrder,
            setPurchaseOrder: function (value) {
                console.log(value);
                purchaseOrder(value);
                quote.extension_attributes.purchase_order = value;
            },
            getPurchaseOrder: function () {
                return purchaseOrder();
            }
        };
    };
});
