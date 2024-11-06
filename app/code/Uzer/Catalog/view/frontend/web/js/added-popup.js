require([
    'jquery',
    'underscore',
    'mage/translate',
    'jquery/ui',
    'mage/url',
], function ($, _, mage, ui, url) {
    $.widget('mage.catalogAddToCartCustomPopup', {
        catalogAddToCartCustomPopup: function () {
            let sku = $('.sku .value');
            if (Array.isArray(sku) && sku.length > 0) {
                sku = sku[0].text();
            } else if (sku) {
                sku = sku.text();
            }
            let productName = $('.page-title span').text();
            let image = $('.fotorama__img').prop('src');
            let concatAttributes = [];
            concatAttributes.push($('.value-material').text());
            let data = $('[data-role=swatch-options]').data('mageSwatchRenderer');
            if (data && data.options) {
                let attributes = data.options.jsonConfig.attributes;
                if (Array.isArray(attributes)) {
                    attributes.forEach((item) => {
                        let value = $(`.swatch-attribute.${item.code} .swatch-attribute-selected-option`).text();
                        if (!value) {
                            let value = $(`.swatch-attribute.${item.code} .swatch-select.${item.code} option:selected`).text();
                            concatAttributes.push(value);
                        } else {
                            concatAttributes.push(value);
                        }
                    });
                }
            }
            let price = $('.normal-price').html();
            let unitText = $.mage.__("Unit");
            let textAttributes = concatAttributes.join(' | ');
            let textPerUnit = $.mage.__('Price per unit');
            let units = $('.total-units').text();
            let unitsText = $.mage.__('Units');
            let html = `
                        <div class="row">
                            <div class="col-md-4">
                                <img src="${image}" alt="${productName}">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 product-added-info">
                                        <h3>${productName}</h3>
                                        <span class="added-details">Ref: ${sku} | ${textAttributes}</span>
                                    </div>
                                    <div class="col-md-12 popup-price"><span>${textPerUnit}: </span>${price} / ${unitText}</div>
                                    <div class="col-md-12 popup-units">${units} ${unitsText}</div>
                                </div>
                            </div>
                        </div>`;
            let popup = $('<div class="add-to-cart-modal-popup"/>').html(html).modal({
                modalClass: 'add-to-cart-popup',
                title: $.mage.__("Added to Cart"),
                buttons: [
                    {
                        text: $.mage.__('Continue Shopping'),
                        class: 'continue',
                        click: function () {
                            this.closeModal();
                        }
                    },
                    {
                        text: $.mage.__('View Cart'),
                        class: 'checkout',
                        click: function () {
                            window.location = url.build('/checkout/cart');
                        }
                    }
                ]
            });
            popup.modal('openModal');
        },
    });
    return $.mage.catalogAddToCartCustomPopup;
});
