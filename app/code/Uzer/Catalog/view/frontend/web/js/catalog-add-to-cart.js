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
	$.widget('mage.catalogAddToCartDecowraps', {
		options: {
			qty: 0,
			box_size: 0,
			available_boxes: 0,
			finalPrice: 0,
			partialBox: 0,
			allowAddPartialBox: false,
			hasAddPartialBox: false,
			allowSelect: false,
			productId: null,
			isDown: false,
			isUp: false,
			selectorBox: $('#boxes'),
			selectorQty: $('#qty'),
			qtyLimitSelector: $('.qty-limit'),
			stockBoxSelector: $('.stock-box'),
			stockUnitsSelector: $('.stock-units'),
			totalsUnitsSelector: $('.total-units'),
			totalsSubtotalSelector: $('.total-subtotal'),
			selectorUnselect: $('.unselected'),
			stockInfoSelector: $('.uzer-product-stock'),
			addToCartButtonSelector: $('#product-addtocart-button'),
			buttonStockNotificationSelector: $('.open-stock-notification-dialog'),
			priceLabelSelector: $('.price-container .price-label'),
			partialBoxContainerSelector: $('.partial-box-container'),
			partialBoxSelector: $('.partial-box-unit'),
			buttonAddPartialBoxSelector: $('.link-add-partial'),
			partialBoxProductAdded: new Map,
			productFormSelector: $('#product_addtocart_form'),
		},
		_init: function () {
			let self = this;
			this.options.buttonAddPartialBoxSelector.click(function () {
				self.addPartialBox();
			});
			$(".product-options-wrapper div").click(function () {
				self.selectProduct();
			});
			let select = $('select');
			select.click(function () {
				self.selectProduct();
			});
			$(document).on('change', 'select', function () {
				self.selectProduct();
			});
			select.change(function () {
				self.selectProduct();
			});
			this.eventBoxesDown();
			this.eventBoxesUp();
			this.eventSelectorBoxChange();
			this.removeMessages();
			self.disableAddToCartButton();
		},
		removeMessages: function () {
			$(document).on('click', '.message-error', function () {
				$(this).hide();
			});
		},
		addPartialBox: function () {
			this.options.isUp = false;
			this.options.isDown = false;
			if (!this.options.partialBoxProductAdded.has(this.options.productId)) {
				this.options.partialBoxProductAdded.set(this.options.productId, this.options.partialBox);
				let val = parseInt(this.options.selectorBox.val());
				this.options.selectorBox.val(val + 1);
				this.options.hasAddPartialBox = true;
			}
			this.options.partialBoxContainerSelector.hide();
			this.calculate();
			if (this.options.hasAddPartialBox) {
				this.addPartialBoxInputs();
			}
		},
		selectProduct: function () {
			let self = this;
			this.options.buttonStockNotificationSelector.hide();
			this.options.qty = 0;
			this.options.box_size = 0;
			this.options.available_boxes = 0;
			this.options.partialBox = 0;
			this.options.allowSelect = false;
			this.options.qtyLimitSelector.hide();
			this.options.selectorUnselect.show();
			this.options.totalsUnitsSelector.text('0');
			this.options.totalsSubtotalSelector.text(this.getFormattedPrice(0));
			this.options.finalPrice = 0;
			let selected_options = {};
			this.options.selectorBox.val(0);
			$('div.swatch-attribute').each(function (k, v) {
				let attribute_id = $(v).attr('attribute-id');
				let option_selected = $(v).attr('option-selected');
				if (!attribute_id || !option_selected) {
					return;
				}
				selected_options[attribute_id] = option_selected;
			});
			let data = $('[data-role=swatch-options]').data('mageSwatchRenderer');
			let product_id_index = data.options.jsonConfig.index;
			console.log(data);
			$.each(product_id_index, function (product_id, attributes) {
				self.initOnChange();
				let productIsSelected = function (attributes, selected_options) {
					return _.isEqual(attributes, selected_options);
				}
				if (productIsSelected(attributes, selected_options)) {
					self.options.productId = product_id;
					self.removeOutOfStockText();
					let box = data.options.jsonConfig.ids[product_id];
					let prices = data.options.jsonConfig.optionPrices[product_id];
					if (prices) {
						self.options.finalPrice = prices.finalPrice.amount;
					}
					self.options.qty = parseInt(box['qty']);
					self.options.box_size = parseInt(box['box_size']);
					self.options.available_boxes = parseInt(box['available_boxes']);
					if (self.options.box_size > 0) {
						if (self.options.available_boxes >= 1) {
							self.options.partialBox = self.options.qty - (self.options.available_boxes * self.options.box_size);
						} else if (self.options.qty > 0 && self.options.qty < self.options.box_size) {
							self.options.partialBox = self.options.qty;
						}
					}
					if (self.options.partialBox > 0) {
						self.options.selectorBox.attr('max', self.options.available_boxes + 1);
					} else {
						self.options.selectorBox.attr('max', self.options.available_boxes);
					}
					if (self.options.box_size > 0) {
						self.options.stockBoxSelector.text(self.options.box_size);
					}
					if (self.options.qty > 0) {
						self.options.buttonStockNotificationSelector.hide();
						self.options.stockUnitsSelector.text(self.options.qty);
					} else {
						self.options.stockUnitsSelector.text('0');
						$('#child_stock_product_id').val(product_id);
						self.options.buttonStockNotificationSelector.show();
					}
					if (self.options.partialBox > 0) {
						self.options.partialBoxContainerSelector.show();
						self.options.partialBoxSelector.text(self.options.partialBox);
						self.options.allowAddPartialBox = true;
					} else {
						self.options.partialBoxContainerSelector.hide();
						self.options.partialBoxSelector.text('0');
						self.options.allowAddPartialBox = false;
					}
					self.options.selectorUnselect.hide();
					self.options.stockInfoSelector.show();
					self.options.allowSelect = true;
					return;
				} else {
				}
			});
		},
		initOnChange: function () {
			this.removePartialBoxInputs();
			this.options.partialBoxProductAdded.delete(this.options.productId);
			this.options.isUp = false;
			this.options.isDown = false;
		},
		eventBoxesDown: function () {
			let self = this;
			$('.boxes-down').click(function () {
				if (!self.options.qty || !self.options.box_size) {
					return;
				}
				if (self.options.allowSelect) {
					self.options.isUp = false;
					self.options.isDown = true;
					let val = parseInt(self.options.selectorBox.val());
					if (val > 0) {
						val = val - 1;
					}
					self.options.selectorBox.val(val).trigger('change');
					self.options.qtyLimitSelector.hide();
				} else {
					self.options.selectorBox.val('0');
				}
				return false;
			});
		},
		eventBoxesUp: function () {
			let self = this;
			$('.boxes-up').click(function () {
				if (!self.options.qty || !self.options.box_size || !self.options.available_boxes) {
					return;
				}
				if (self.options.allowSelect) {
					self.options.isUp = true;
					self.options.isDown = false;
					let val = parseInt(self.options.selectorBox.val());
					let availableBoxes = self.options.available_boxes;
					if (self.options.partialBoxProductAdded.has(self.options.productId)) {
						availableBoxes = availableBoxes + 1;
					}
					if (val < availableBoxes) {
						val = val + 1;
						self.options.selectorBox.val(val).trigger('change');
						self.options.qtyLimitSelector.hide();
					} else {
						self.options.qtyLimitSelector.show();
					}
				} else {
					self.options.selectorBox.val('0');
				}
				return false;
			});
		},
		eventSelectorBoxChange: function () {
			let self = this;
			this.options.selectorBox.change(function () {
				if (self.options.allowSelect) {
					self.calculate();
				} else {
					self.options.qtyLimitSelector.show();
					self.options.selectorBox.val('0');
					self.options.selectorQty.val('0');
				}
				return false;
			});
		},
		getFormattedPrice: function (price) {
			let globalPriceFormat = {
				requiredPrecision: 2,
				precision: 2
			};
			return priceUtils.formatPrice(price, globalPriceFormat);
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
			if (val <= 0) {
				this.options.selectorQty.val('0');
				this.options.totalsUnitsSelector.text('0');
				this.options.totalsSubtotalSelector.text(this.getFormattedPrice(0));
				if (this.options.hasAddPartialBox) {
					this.removePartialBoxInputs();
				}
				this.disableAddToCartButton();
			} else {
				let hasPartialBox = this.options.partialBoxProductAdded.has(this.options.productId);
				if (hasPartialBox) {
					this.calculateWithPartialBox(val);
				} else {
					this.calculateWithOutPartialBox(val);
				}
				this.enableAddToCartButton();
			}
			this.options.isUp = false;
			this.options.isDown = false;
		},
		calculateWithPartialBox: function (boxes = 1) {
			if (this.options.isDown) {
				this.options.partialBoxProductAdded.delete(this.options.productId);
				this.calculateWithOutPartialBox(boxes);
				this.removePartialBoxInputs();
			} else {
				let totalQty = (boxes - 1) * this.options.box_size + this.options.partialBoxProductAdded.get(this.options.productId);
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
		enableAddToCartButton: function () {
			this.options.addToCartButtonSelector.prop('disabled', false);
			this.options.addToCartButtonSelector.removeClass('disabled');
		},
		disableAddToCartButton: function () {
			this.options.addToCartButtonSelector.prop('disabled', true);
			this.options.addToCartButtonSelector.addClass('disabled');
		},
		isValid: function (value) {
			return value !== undefined && !isNaN(value);
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
			this.options.partialBoxProductAdded.delete(this.options.productId);
			if (this.options.partialBox > 0) {
				this.options.partialBoxContainerSelector.show();
			}
		},
		removeOutOfStockText: function () {
			const swatchSelect = document.querySelector('.swatch-select');
			swatchSelect.querySelectorAll('.stock.out-of-stock').forEach(element => element.remove());
		}
	});
	return $.mage.catalogAddToCartDecowraps;
});
