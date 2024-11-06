require([
    'jquery',
    'underscore',
    'mage/translate',
    'jquery/ui',
], function ($, _, mage, priceUtils) {
    $.widget('mage.removeOrAddToWish', {
        removeSelector: '.action-delete',
        openDialog: function (dataDelete, dataWishlist) {
            let self = this;
            let popup = $('<div class="remove-modal-popup-content"/>').html(self.getTemplate()).modal({
                modalClass: 'remove-modal-popup',
                title: $.mage.__(""),
                buttons: [
                    {
                        text: $.mage.__('Delete'),
                        class: 'delete',
                        click: function () {
                            self.submitForm(dataDelete);
                        }
                    },
                    {
                        text: $.mage.__('Move to wishlist'),
                        class: 'towishlist',
                        click: function () {
                            self.submitForm(dataWishlist);
                        }
                    }
                ]
            });
            popup.modal('openModal');
        },
        getTemplate: function () {
            let text = $.mage.__('Before removing this product from your cart, would you like to save it to your wishlist?');
            let html = `
                <div class="row">
                    <div class="col-md-12">
                        <p>${text}</p>
                    </div>
                </div>`;
            return text;
        },
        submitForm: function (data) {
            console.log(data);
            let formSelector = $(`<form action="${data.action}" method="post"></form>`);
            $.each(data.data, function (key, value) {
                formSelector.append(`<input type="hidden" name="${key}" value="${value}">`);
            });
            formSelector.appendTo('body');
            formSelector.submit();
        }
    });
});
