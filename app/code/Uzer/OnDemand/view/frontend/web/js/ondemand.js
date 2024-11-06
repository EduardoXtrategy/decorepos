require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'Magento_Ui/js/model/messageList',
        'mage/translate'
    ],
    function ($, modal, messageManager) {
        $(document).ready(function () {
            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: $.mage.__('Please complete the below fields and we\'ll contact you as soon as possible.'),
                modalClass: 'ondemand-modal',
            };
            let selector = '#modal-content-add-product-ondemand';
            if ($(selector).length > 0) {
                let element = $(selector);
                if (element) {
                    modal(options, element);
                    $("#modal-btn-ondemand").on('click', function () {
                        $("#modal-content-add-product-ondemand").modal("openModal");
                    });
                }
            };

            $('.ondemand-modal .modal-footer').hide();

            // setTimeout(function () {
            //     $('.page.messages').hide();
            // }, 5000);
            // let selectorForm = '#ondemand-form';
            // $(selectorForm).submit(function (e) {
            //     e.preventDefault();
            //     let formData = new FormData($(selectorForm)[0]);
            //     let uri = $(selectorForm).attr('action');
            //     $.ajax({
            //         url: uri,
            //         type: "POST",
            //         enctype: 'multipart/form-data',
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function (data) {
            //             if (data.result === 'success') {
            //                 console.log('success');
            //                 console.log(data);
            //                 messageManager.addSuccessMessage({message: $.mage.__('Your request has been made.')});
            //                 $("#modal-content-add-product-ondemand").modal("closeModal");
            //             } else if (data.result === 'error') {
            //                 console.log('error');
            //                 console.log(data);
            //                 messageManager.addSuccessMessage({message: $.mage.__('An error has occurred, please try again')});
            //                 $("#modal-content-add-product-ondemand").modal("closeModal");
            //             }
            //         },
            //         error: function (xhr, error) {
            //             console.log(xhr);
            //             console.log(error);
            //         }
            //     });
            // });
        });

        // Recaptcha validation
        function validarRecaptcha(event) {
            var recaptchaResponse = grecaptcha.getResponse();

            if (recaptchaResponse.length === 0) {
                // Show alert
                alert("Please complete the Recaptcha before submitting the form.");
                event.preventDefault(); // Stop form sending      
            } else {
                // Change button when send
                $(".samples-button").text("Sending...");
                $(".samples-button").prop("disabled", true);      
                // Recaptcha OK, send form
            }
        }

        // Add listener to form
        $("#ondemand-form").on("submit", validarRecaptcha);
    }
);
