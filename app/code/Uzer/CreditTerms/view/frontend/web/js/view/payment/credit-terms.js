define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'terms',
            component: 'Uzer_CreditTerms/js/view/payment/method-renderer/credit-terms'
        }
    );

    /** Add view logic here if you needed */
    return Component.extend({});
});
