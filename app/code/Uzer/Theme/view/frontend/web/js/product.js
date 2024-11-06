require(['jquery', 'underscore', 'mage/translate'], function($, _, mage) {
	let addedContent = false;
	$(document).ready(function() {
		tooltip();
		$('.newlabel').hide();
		$('#uzer-sku').hide();
		$('.onsale').hide();
		$(".product-options-wrapper div").click(function() {
			selectedProduct();
		});
		let select = $('select');
		select.click(function() {
			selectedProduct();
		});
		$(document).on('change', 'select', function() {
			selectedProduct();
		});
		select.change(function() {
			selectedProduct();
		});
	});

	function tooltip() {
		$(document).on('mouseover', '.product-options-wrapper .header .swatch-attribute-label', function() {
			let data = $('[data-role=swatch-options]').data('mageSwatchRenderer');
			if (data.options.jsonConfig.hasHeader) {
				if (!addedContent) {
					let content = data.options.jsonConfig.headerContent;
					$('.product-options-wrapper .header').append('<div class="header-layer" style="display: none">' + content + '</div>');
					addedContent = true;
				}
				$('.product-options-wrapper .header .header-layer').show();
			}
		});
		$(document).on('mouseleave', '.product-options-wrapper .header .swatch-attribute-label', function() {
			$('.product-options-wrapper .header .header-layer').hide();
		});
	}

	function selectedProduct() {
		var selected_options = {};
		$('div.swatch-attribute').each(function(k, v) {
			var attribute_id = $(v).attr('attribute-id');
			var option_selected = $(v).attr('option-selected');
			if (!attribute_id || !option_selected) {
				return;
			}
			selected_options[attribute_id] = option_selected;
		});

		let data = $('[data-role=swatch-options]').data('mageSwatchRenderer');
		var product_id_index = data.options.jsonConfig.index;
		let found_ids = [];
		$.each(product_id_index, function(product_id, attributes) {
			let productIsSelected = function(attributes, selected_options) {
				return _.isEqual(attributes, selected_options);
			}
			if (productIsSelected(attributes, selected_options)) {
				let simpleSku = data.options.jsonConfig.sku[product_id];
				let sale = data.options.jsonConfig.skus[product_id].sale;
				let isNew = data.options.jsonConfig.skus[product_id].is_new;
				let availableMessage = data.options.jsonConfig.skus[product_id].available_message;
				let isSustainable = data.options.jsonConfig.skus[product_id].sustainable;
				if (simpleSku) {
					$('#uzer-sku').show();
					$('div.product-info-main .sku .value').html(simpleSku);
				}
				if (sale > 0) {
					$('.label-onsale').show();
				} else {
					$('.label-onsale').hide();
				}
				if (isNew) {
					$('.label-newlabel').show();
				} else {
					$('.label-newlabel').hide();
				}
				if (availableMessage) {
					$('.available-message-container').show();
					$('.availabe-message-text').html(availableMessage);
				} else {
					$('.available-message-container').hide();
				}
				if (isSustainable) {
					$('.label-sustainable').show();
				} else {
					$('.label-sustainable').hide();
				}
				let skuOptions = data.options.jsonConfig.skus;
				let perforation = skuOptions[product_id].perforation;
				let box_quantity = skuOptions[product_id].box_quantity;
				let perforationSelector = $('.product-custom-option');
				if (perforationSelector) {
					if (perforation === '0' || perforation === null || perforation === undefined || parseInt(perforation) === 0) {
						perforationSelector.attr('disabled', 'disabled');
						perforationSelector.val("");
					} else {
						perforationSelector.attr('disabled', false);
						perforationSelector.val("");
					}
				}
				if (box_quantity) {
					let html = $.mage.__('- Box (%1 Units)').replace('%1', box_quantity);
					$('.box-quantity').html(html);
				}
			}
		});
	}

	function setMarketingColor(marketingColor) {
		$('.product-options-wrapper .swatch-opt .color .swatch-attribute-selected-option').html(marketingColor);
	}
});
