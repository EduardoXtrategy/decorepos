/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'mage/translate',
    'mage/url',
    'jquery/ui',
    'Magento_Catalog/js/catalog-add-to-cart',
    'catalogAddToCartCustomPopup'
], function ($, $t, url, catalogAddToCartCustomPopup) {
    "use strict";

    $.widget('mage.catalogAddToCart', {

        options: {
            processStart: null,
            processStop: null,
            bindSubmit: true,
            minicartSelector: '[data-block="minicart"]',
            messagesSelector: '[data-placeholder="messages"]',
            productStatusSelector: '.stock.available',
            addToCartButtonSelector: '.action.tocart',
            addToCartButtonDisabledClass: 'disabled',
            addToCartButtonTextWhileAdding: $t('Adding...'),
            addToCartButtonTextAdded: $t('Added'),
            addToCartButtonTextDefault: $t('Add to Cart'),
            addToCartButtonSelectorCustom: '#product-addtocart-button',
        },

        _create: function () {
            if (this.options.bindSubmit) {
                this._bindSubmit();
            }
        },

        _bindSubmit: function () {
            var self = this;
            this.element.on('submit', function (e) {
                e.preventDefault();
                self.submitForm($(this));
            });
        },

        isLoaderEnabled: function () {
            return this.options.processStart && this.options.processStop;
        },

        submitForm: function (form) {
            var self = this;
            if (form.has('input[type="file"]').length && form.find('input[type="file"]').val() !== '') {
                self.element.off('submit');
                form.submit();
            } else {
                self.ajaxSubmit(form);
            }
        },

        ajaxSubmit: function (form) {
            console.log('enter here');
            var self = this;
            self.disableAddToCartButton(form);
            var url = form.attr('action');
            $.ajax({
                url: url,
                data: form.serialize(),
                type: 'post',
                dataType: 'json',
                beforeSend: function () {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStart);
                    }
                    $('body').append('<div id="add-to-cart-loading-ajax-common"><span></span></div>');
                },
                success: function (res) {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStop);
                    }

                    if (res.backUrl) {
                        window.location = res.backUrl;
                        return;
                    }
                    $(self.options.minicartSelector).trigger('contentUpdated');

                    if (res.messages) {
                        $(self.options.messagesSelector).html(res.messages);
                    }
                    if (res.minicart) {
                        $(self.options.minicartSelector).replaceWith(res.minicart);
                        $(self.options.minicartSelector).trigger('contentUpdated');
                    }
                    if (res.product && res.product.statusText) {
                        $(self.options.productStatusSelector)
                            .removeClass('available')
                            .addClass('unavailable')
                            .find('span')
                            .html(res.product.statusText);
                    }

                    var width_window = $(window).width();
                    if (width_window > 480) {
                        window.ajaxCartTransport = true;
                    } else {
                        $('body #add-to-cart-loading-ajax-common').remove();
                        if (res.html) {
                        }
                    }
                    self.enableAddToCartButton(form);
                    self.modalAddedToCart();
                }
            });
        },

        modalAddedToCart: function () {
            $.mage.catalogAddToCartCustomPopup.prototype.catalogAddToCartCustomPopup();
        },

        disableAddToCartButton: function (form) {
            let addToCartButton = $(form).find(this.options.addToCartButtonSelector);
            addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
            addToCartButton.attr('title', this.options.addToCartButtonTextWhileAdding);
            addToCartButton.find('span').text(this.options.addToCartButtonTextWhileAdding);
            addToCartButton.prop('disabled', true);
            let addToCartButtonSelector = $(this.options.addToCartButtonSelectorCustom);
            addToCartButtonSelector.addClass(this.options.addToCartButtonDisabledClass);
            addToCartButtonSelector.attr('title', this.options.addToCartButtonTextWhileAdding);
            addToCartButtonSelector.find('span').text(this.options.addToCartButtonTextWhileAdding);
            addToCartButtonSelector.prop('disabled', true);
        },

        enableAddToCartButton: function (form) {
            let self = this,
                addToCartButton = $(form).find(this.options.addToCartButtonSelector),
                addToCartButtonSelector = $(this.options.addToCartButtonSelectorCustom);

            addToCartButton.find('span').text(this.options.addToCartButtonTextAdded);
            addToCartButton.attr('title', this.options.addToCartButtonTextAdded);
            addToCartButtonSelector.find('span').text(this.options.addToCartButtonTextAdded);
            addToCartButtonSelector.attr('title', this.options.addToCartButtonTextAdded);

            setTimeout(function () {
                addToCartButton.removeClass(self.options.addToCartButtonDisabledClass);
                addToCartButton.find('span').text(self.options.addToCartButtonTextDefault);
                addToCartButton.attr('title', self.options.addToCartButtonTextDefault);
                addToCartButton.prop('disabled', false);
                addToCartButtonSelector.removeClass(self.options.addToCartButtonDisabledClass);
                addToCartButtonSelector.find('span').text(self.options.addToCartButtonTextDefault);
                addToCartButtonSelector.attr('title', self.options.addToCartButtonTextDefault);
                addToCartButtonSelector.prop('disabled', false);
            }, 1000);
        }
    });

    return $.mage.catalogAddToCart;
});
