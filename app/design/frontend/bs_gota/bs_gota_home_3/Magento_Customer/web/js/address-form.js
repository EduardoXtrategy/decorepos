require([
	'jquery',
	'Magento_Ui/js/modal/modal',
	'Magento_Ui/js/model/messageList',
	'Magento_Ui/js/modal/alert',
],
	function($, modal, messageList, alertDialog) {
		$(document).ready(function() {
			$('.form-address-edit').submit(function(event) {
				$(this).validation({

					/**
					 * Submit Handler
					 * @param {Element} form - address form
					 */
					submitHandler: function(form) {
						alert('Enter here');
						$('.action.save').attr('disabled', true);
					}
				});
			});
		});
	});