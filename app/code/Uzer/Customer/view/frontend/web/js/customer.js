require([
        'jquery',
        'Magento_Ui/js/modal/modal',
        'Magento_Ui/js/model/messageList',
        'Magento_Ui/js/modal/alert',
    ],
    function ($, modal, messageList, alertDialog) {
        $(document).ready(function () {
            $('#region').hide();
            $('#shipping-address-form').submit(function (evt) {
                if (!$('#shipping-address-form').valid()) {
                    evt.preventDefault();
                }
            });
            let dataForm = $('#billing-addres-form');
            $('button#btn-edit-address').click(function () { //can be replaced with any event
                dataForm.validation('isValid'); //validates form and returns boolean
            });
            let selector = $('input[name="address_id"]:checked');
            if (selector.length > 0) {
                let item = selector.get(0);
                if (item) {
                    $('#address_code').val($(item).val());
                }
            }
            $("#country").on('change', function () {
                $("#country option:selected").each(function () {
                    var code_coun = $(this).val();
                    Regions(code_coun, 0);

                });
            });
            let addressId = 'address-' + id;
            $('#' + addressId).addClass('address-active');
            let checkbox = 'address-checkbox-' + id;
            $('#' + checkbox).prop('checked', true);
        });

        function Regions(country, region) {
            $.ajax({
                url: '/samples/address/regions/',
                type: 'get',
                data: {
                    code: country
                },
                dataType: 'json',
                success: function (data) {
                    $("#region-sa").empty();
                    let regions = data.region_address;

                    if (regions.totalRecords > 1) {
                        $('#region').hide();
                        $('#region-sa').show();
                        for (let i = 0; i < regions.totalRecords; i++) {
                            $("#region-sa").append('<option value="' + regions.items[i].code + '">' + regions.items[i].name + '</option>');
                        }

                        if (region.length > 1) {
                            $("#region-sa > option[value=" + region + "]").attr("selected", true);
                        }
                    } else {
                        $('#region').show();
                        $("#region-sa").html('');
                        $('#region-sa').hide();
                    }
                },
                error: function (xhr, status, errorThrown) {
                    console.log('error');
                }
            });
        }
    });


/*
By Osvaldas Valutis, www.osvaldas.info
Available for use under the MIT License
*/

'use strict';

;(function (document, window, index) {
    let inputs = document.querySelectorAll('.inputfile');
    Array.prototype.forEach.call(inputs, function (input) {
        let label = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener('change', function (e) {
            let fileName = '';
            if (this.files && this.files.length > 0) {
                let file = this.files[0];
                if (file && file.type === 'application/pdf') {
                    fileName = file.name;
                } else {
                    input.value = null;
                }
            }
            if (fileName) {
                label.querySelector('span').innerHTML = fileName;
            } else {
                label.innerHTML = labelVal;
            }
        });

        // Firefox bug fix
        input.addEventListener('focus', function () {
            input.classList.add('has-focus');
        });
        input.addEventListener('blur', function () {
            input.classList.remove('has-focus');
        });
    });
}(document, window, 0));


require([
    "jquery",
    "mage/calendar"
], function ($) {
    $("#due_date").calendar({
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        minDate: new Date()
    });
});


//Dinamic required tax exemption expiration Date

require(['jquery'], function ($) {
    $(document).ready(function () {

        $('#tax_certificate').change(function () {
            if ($(this).val() != '') {
                $('#due_date').prop('required', true);
            } else {
                $('#due_date').prop('required', false);
                $('.due-date > .mage-error').hide();
            }
        });

        $('#billing-addres-form').submit(function () {
            var archivo = $('#tax_certificate').val();
            var fecha = $('#datepicker').val();

            if (archivo != '' && fecha == '') {
                alert('You must select a date if you have uploaded a file.');

                return false;
            }
            return true;
        });
    });
});


//Max size input


require(['jquery'], function ($) {
    $(document).ready(function () {
        $('input[type="file"]').change(function () {
            var fileSize = this.files[0].size;
            if (fileSize > 5000000) {
                alert('File size must be less than 5 MB');
                $(this).val('');
                $(this).closest('.box').find('label[for="new_customer_form"] > span').text('Upload the New Customer Form');
                $(this).closest('.box').find('label[for="credit_application"] > span').text('Upload Credit Application Form');
                $(this).closest('.box').find('label[for="tax_certificate"] > span').text('Upload Tax Exemption Certificate');
                $(this).closest('.box').find('label[for="wp9"] > span').text('Upload W9 Document');
                $(this).closest('.box').find('label[for="customer_responsability_agreement"] > span').text('Upload the Costumer Agreement');
                $(this).closest('.box').find('label[for="circular_format_170"] > span').text('Upload the Circular Formar 170');
                $(this).closest('.box').find('label[for="data_agreement"] > span').text('Upload the Personal Data Agreement');
                $(this).closest('.box').find('label[for="commerce_certificate"] > span').text('Upload Chamber of Commerce Certificate');
                $(this).closest('.box').find('label[for="rut"] > span').text('Upload RUT document');
                $(this).closest('.box').find('label[for="legal_representative"] > span').text('Upload Legal Representative´s ID');
                $(this).closest('.box').find('label[for="commercial_reference"] > span').text('Upload Commercial Reference');
                $(this).closest('.box').find('label[for="bank_reference"] > span').text('Upload Bank Reference');
                $(this).closest('.box').find('label[for="copy_appointment"] > span').text('Upload Copy of the Appointment');
                $(this).closest('.box').find('label[for="ruc"] > span').text('Upload RUC Document');
            }
        });
    });
});


//Numeric input only and limit NIT


require(['jquery', 'jquery/ui'], function ($) {
    $(document).ready(function () {
        $(".account_tax").on('keypress', function (e) {
            if (this.value.length > 20) {
                this.value = this.value.slice(0, 20);
            }
        });
    });

    $(".nit-field").on('input', function () {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 9) {
            this.value = this.value.slice(0, 9);
        }
    });
});
