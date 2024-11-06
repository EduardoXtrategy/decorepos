require([
    'jquery',
    'underscore',
    'mage/translate',
    'Magento_Catalog/js/price-utils',
    'Magento_Customer/js/model/customer',
    'Magento_Customer/js/customer-data',
    'mage/url',
    'catalogAddToCartDecowraps'
], function (
    $,
    _,
    mage,
    priceUtils,
    customer,
    customerData,
    url,
    catalogAddToCartDecowraps
) {
    $(document).ready(function () {
        setTimeout(function () {
            console.log($.mage);
            $.mage.catalogAddToCartDecowraps.prototype._init();
        }, 200);
    });
});
