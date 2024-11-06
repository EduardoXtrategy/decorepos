require([
    'jquery',
    'underscore',
    'mage/translate',
    'Magento_Ui/js/modal/modal',
    'mage/loader',
    'mage/validation'
], function (
    $,
    _,
    mage,
    modal,
    loader
) {
    $.widget('mage.salesReturnProducts', {
        options: {
            modalSelector: '#return-popup',
            formSelector: '#return-form',
            popup: null
        },
        __init: function () {
        },
        openPopup: function () {
            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: $.mage.__('Return Products'),
                modalClass: 'return-products-modal',
                buttons: []
            };
            let selector = $(this.options.modalSelector);
            this.popup = modal(options, selector);
            selector.modal('openModal');
        },
        sendForm: function (element, action) {
            let formData = new FormData(element);
            $('body').loader('show');
            let self = this;
            $.ajax({
                url: action,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('body').loader('hide');
                    self.popup.closeModal();
                    self.success();
                },
                error: function (xhr, status, error) {
                    $('body').loader('hide');
                    self.popup.closeModal();
                }
            });
        },
        success: function () {
            let title = $.mage.__('Request Sent');
            let text = $.mage.__('We have successfully received your request. Our Customer Service Team will review the information provided and contact you as soon as possible');
            let html = `
                <div class="row">
                    <div class="col-12">
                    <img src="https://staging.decowraps.com/static/frontend/bs_gota/bs_gota_home_3/en_US/css/images/ico-return-request.svg" class="return-request-logo" alt="Request Sent">
                    </div>
                    <div class="col-12">
                        <h3>${title}</h3>
                    </div>
                    <div class="col-12">
                        <p>${text}</p>
                    </div>
                </div>
            `;
            let popup = $('<div class="success-popup"/>').html(html).modal({
                modalClass: 'return-products-success-popup',
                title: $.mage.__('Added to Cart'),
                type: 'popup',
                responsive: true,
                innerScroll: true,
                buttons: [
                    {
                        text: $.mage.__('Finish'),
                        class: 'action finish',
                        click: function () {
                            this.closeModal();
                        }
                    },
                ]
            });
            popup.modal('openModal');
        }
    });
    return $.mage.salesReturnProducts;
});
