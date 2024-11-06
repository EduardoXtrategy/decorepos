require([
    'jquery',
    'underscore',
    'mage/translate',
    'Magento_Catalog/js/price-utils',
], function (
    $,
    _,
    mage,
    priceUtils
) {
    $.widget('mage.simpleAddToCartDecowraps', {
        options: {
            qty: 0,
            box_size: 0,
            available_boxes: 0,
            partialBox: 0,
            isDown: false,
            isUp: false,
            finalPrice: 0,
            selectorBox: $('#boxes'),
            selectorQty: $('#qty'),
            qtyLimitSelector: $('.qty-limit'),
            stockBoxSelector: $('.stock-box'),
            stockUnitsSelector: $('.stock-units'),
            totalsUnitsSelector: $('.total-units'),
            totalsSubtotalSelector: $('.total-subtotal'),
            addToCartButtonSelector: $('#product-addtocart-button'),
            buttonStockNotificationSelector: $('.open-stock-notification-dialog'),
            partialBoxSelector: $('.partial-box-unit'),
            buttonAddPartialBoxSelector: $('.link-add-partial'),
            productFormSelector: $('#product_addtocart_form'),
            partialBoxContainerSelector: $('.partial-box-container'),
            partialBoxAdded: false
        },
        _init: function () {
            let self = this;
            let data = $('[data-role="uzer-product-view"]').data('product');
            let price = $('[data-price-type="finalPrice"]').data('price-amount');
            this.options.finalPrice=parseFloat(price);
            this.options.qty = data.qty;
            this.options.box_size = data.box_size;
            this.options.available_boxes = data.available_boxes;
            this.options.partialBox = data.partial_boxes;
            this.initOnChange();
            this.eventBoxesDown();
            this.eventBoxesUp();
            this.eventSelectorBoxChange();
            this.disableAddToCartButton();
            this.options.buttonAddPartialBoxSelector.click(function () {
                self.addPartialBox();
            });
        },
        initOnChange: function () {
            this.removePartialBoxInputs();
            this.options.partialBoxAdded = false;
            this.options.isUp = false;
            this.options.isDown = false;
        },
        removeMessages: function () {
            $(document).on('click', '.message-error', function () {
                $(this).hide();
            });
        },
        addPartialBox: function () {
            this.options.isUp = false;
            this.options.isDown = false;
            console.log(this.options.partialBoxAdded);
            if (!this.options.partialBoxAdded) {
                this.options.partialBoxAdded = true;
                let val = parseInt(this.options.selectorBox.val());
                this.options.selectorBox.val(val + 1).trigger('change');
                this.addPartialBoxInputs();
            }
            this.options.partialBoxContainerSelector.hide();
            // this.calculate();
        },
        addPartialBoxInputs: function () {
            this.options.productFormSelector.append(`<input type="hidden" name="partial_box" value="1">`);
            this.options.productFormSelector.append(`<input type="hidden" name="custom_product_id" value="${this.options.productId}">`);
            this.options.productFormSelector.append(`<input type="hidden" name="partial_box_qty" value="${this.options.partialBox}">`);
            this.options.partialBoxContainerSelector.hide();
        },
        removePartialBoxInputs: function () {
            this.options.productFormSelector.find(`input[name="partial_box"]`).remove();
            this.options.productFormSelector.find(`input[name="custom_product_id"]`).remove();
            this.options.productFormSelector.find(`input[name="partial_box_qty"]`).remove();
            if (this.options.partialBox > 0) {
                this.options.partialBoxContainerSelector.show();
            }
        },
        eventBoxesDown: function () {
            let self = this;
            $('.boxes-down').click(function () {
                if (!self.options.qty || !self.options.box_size) {
                    return;
                }
                self.options.isUp = false;
                self.options.isDown = true;
                let val = parseInt(self.options.selectorBox.val());
                if (val > 0) {
                    val = val - 1;
                }
                self.options.selectorBox.val(val).trigger('change');
                self.options.qtyLimitSelector.hide();
                return false;
            });
        },
        eventBoxesUp: function () {
            let self = this;
            $('.boxes-up').click(function () {
                if (!self.options.qty || !self.options.box_size || !self.options.available_boxes) {
                    return;
                }
                self.options.isUp = true;
                self.options.isDown = false;
                let val = parseInt(self.options.selectorBox.val());
                let availableBoxes = self.options.available_boxes;
                if (self.options.partialBoxAdded === true) {
                    availableBoxes = availableBoxes + 1;
                }
                if (val < availableBoxes) {
                    val = val + 1;
                    self.options.selectorBox.val(val).trigger('change');
                    self.options.qtyLimitSelector.hide();
                } else {
                    self.options.qtyLimitSelector.show();
                }
            });
        },
        eventSelectorBoxChange: function () {
            let self = this;
            this.options.selectorBox.change(function () {
                self.calculate();
            });
        },
        enableAddToCartButton: function () {
            this.options.addToCartButtonSelector.prop('disabled', false);
            this.options.addToCartButtonSelector.removeClass('disabled');
        },
        disableAddToCartButton: function () {
            this.options.addToCartButtonSelector.prop('disabled', true);
            this.options.addToCartButtonSelector.addClass('disabled');
        },
        calculateWithPartialBox: function (boxes = 1) {
            if (this.options.isDown) {
                this.options.partialBoxAdded = false;
                this.calculateWithOutPartialBox(boxes);
                this.removePartialBoxInputs();
            } else {
                let totalQty = (boxes - 1) * this.options.box_size + this.options.partialBox;
                this.calculatePrice(totalQty);
            }
        },
        calculateWithOutPartialBox: function (boxes = 1) {
            let totalQty = boxes * this.options.box_size;
            this.calculatePrice(totalQty);

        },
        calculatePrice: function (totalQty = 1) {
            this.options.selectorQty.val(totalQty);
            this.options.totalsUnitsSelector.text(totalQty);
            let subtotal = totalQty * this.options.finalPrice;
            this.options.totalsSubtotalSelector.text(this.getFormattedPrice(subtotal));
            this.enableAddToCartButton();
        },
        calculate: function () {
            if (!this.isValid(this.options.qty) || !this.isValid(this.options.box_size) || !this.isValid(this.options.available_boxes)) {
                this.options.isUp = false;
                this.options.isDown = false;
                return;
            }
            if (this.options.box_size <= 0 && this.options.partialBox <= 0) {
                this.options.selectorQty.val('0');
                this.options.totalsUnitsSelector.text('0');
                this.options.totalsSubtotalSelector.text(this.getFormattedPrice(0));
                this.options.partialBoxProductAdded.delete(this.options.productId);
                this.options.partialBoxContainerSelector.hide();
                this.options.isUp = false;
                this.options.isDown = false;
                return;
            }
            let val = parseInt(this.options.selectorBox.val());
            console.log(val);
            if (val <= 0) {
                this.options.selectorQty.val('0');
                this.options.totalsUnitsSelector.text('0');
                this.options.totalsSubtotalSelector.text(this.getFormattedPrice(0));
                console.log(this.options.partialBox);
                if (this.options.partialBox > 0) {
                    this.options.partialBoxAdded = false;
                    this.removePartialBoxInputs();
                }
                this.disableAddToCartButton();
            } else {
                if (this.options.partialBoxAdded) {
                    this.calculateWithPartialBox(val);
                } else {
                    this.calculateWithOutPartialBox(val);
                }
                this.enableAddToCartButton();
            }
            this.options.isUp = false;
            this.options.isDown = false;
        },
        isValid: function (value) {
            return value !== undefined && !isNaN(value);
        },
        getFormattedPrice: function (price) {
            let globalPriceFormat = {
                requiredPrecision: 2,
                precision: 2
            };
            return priceUtils.formatPrice(price, globalPriceFormat);
        },
    });
});
