define([
    'jquery',
    'mage/utils/wrapper',
    'mage/translate',
    'Magento_Checkout/js/model/place-order',
    'Magento_Checkout/js/model/quote',
    'jquery/ui',
], function ($, wrapper, mage, placeOrder, quote, ui) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, redirectOnSuccess, messageContainer) {
            paymentData.po_number = $('[name="additional_data[purchaseOrder]"]').val();
            if (paymentData.po_number && paymentData.po_number.length > 14) {
                let html = `
                        <div class="row po-number-container">
                            <div class="po-number-container-inner">
                                <div class="row po-number-box">
                                    <div class="col-md-12 po-number-info">
                                        <span class="added-details">${$.mage.__("Purchase Order number exceeds the limit.")}</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                let popup = $('<div class="error-popup"/>').html(html).modal({
                    modalClass: 'po-number-popup',
                    title: $.mage.__('Attention'),
                    buttons: [
                        {
                            text: $.mage.__('Ok'),
                            class: 'continue',
                            click: function () {
                                this.closeModal();
                            }
                        }
                    ]
                });
                popup.modal('openModal');
                return $.Deferred().reject('').promise();
            }
            return originalAction();
        });
    };
});
