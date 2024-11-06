/*------- Modal Samples Product -------*/

require([
        'jquery',
        'Magento_Ui/js/modal/modal',
        'Magento_Ui/js/model/messageList',
        'Magento_Ui/js/modal/alert',
    ],
    function ($, modal, messageList, alertDialog) {

        let formSelector = $('#shipping-address-form');
        $(document).ready(function () {
            $('#region').hide();
            formSelector.submit(function (evt) {
                if (!formSelector.valid()) {
                    evt.preventDefault();
                }
            });

            $('#btn-edit-address').click(function () {
                if (formSelector.validation('isValid')) {
                    formSelector.submit();
                }
            });

            $('button#btn-edit-address').click(function () { //can be replaced with any event
                formSelector.validation('isValid'); //validates form and returns boolean
            });

            let selector = $('input[name="address_id"]:checked');
            if (selector.length > 0) {
                let item = selector.get(0);
                if (item) {
                    $('#address_code').val($(item).val());
                }
            }
            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: $.mage.__('Add New Address'),
                modalClass: 'samples-address',
                buttons: []
            };

            modal(options, $('#modal-content-add-shipping'));
            $("#modal-btn-shipping").on('click', function () {
                $('.modal-title').html($.mage.__('Add New Address'));
                $('#key_address').val('');
                $('#first_name').val('');
                $('#last_name').val('');
                $('#company').val('');
                $("#country").val('');
                $("#region-sa").val('');
                $('#city').val('');
                $('#street_1').val('');
                $('#street_2').val('');
                $('#street_3').val('');
                $('#zip_code').val('');
                $('#telephone').val('');
                $('#btn-edit-address').html($.mage.__('Add Address'));
                $("#modal-content-add-shipping").modal("openModal");
            });

            $('#samples-cart-delivery-address').submit(function (event) {
                if ($('#address_code').val() <= 0) {
                    event.preventDefault();
                    errorAddress();
                }
            });

            $('#samples-button-summary-checkout').click(function () {
                if ($('#address_code').val() <= 0) {
                    errorAddress();
                } else {
                    $('#samples-cart-delivery-address').submit();
                }
            });

            $(".shipping_id").click(function (event) {
                var options2 = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: $.mage.__('Edit Address')
                };
                modal(options2, $('.modal-content-edit-shipping'));
                $(".modal-content-edit-shipping").modal("openModal");
                var id_address = $(this).attr('data-id');
                var promise = $.ajax({
                    url: '/samples/address/edit/',
                    type: 'get',
                    data: {
                        id: id_address
                    },
                    dataType: 'json',
                    success: function (data) {
                        $('#key_address').val(data.address.entity_id);
                        $('#first_name').val(data.address.firstname);
                        $('#last_name').val(data.address.lastname);
                        $('#company').val(data.address.company);
                        $("#country > option[value=" + data.address.country_id + "]").attr("selected", true);
                        $('#city').val(data.address.city);
                        $('#zip_code').val(data.address.postcode);
                        $('#telephone').val(data.address.telephone);
                        $('#btn-edit-address').html($.mage.__('Save'));
                        //console.log(data.address);


                        let dir_fort = data.address.street.replace("\n", '---');
                        let dir = dir_fort.split("---");
                        $('#street_1').val(dir[0]);
                        if (dir.length > 1) {
                            let dir_a_fort = dir[1].replace("\n", '---');
                            let dir_a = dir_a_fort.split("---");
                            if (dir_a.length > 0) {
                                $('#street_2').val(dir_a[0]);
                                $('#street_3').val(dir_a[1]);
                            }
                        }

                        let country_code = data.address.country_id;
                        let region_code = data.address.region;
                        console.log(region_code);
                        Regions(country_code, region_code);

                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('error');
                    }
                });
            });
        });

        $("#country").on('change', function () {
            $("#country option:selected").each(function () {
                var code_coun = $(this).val();
                Regions(code_coun, 0);

            });
        });

        $(document).ready(function () {
            $(".btn-back").click(function (event) {
                $(location).attr('href', '/samples/cart/');
            });
            $(".icon-down").click(function () {
                $(this).toggleClass("icon-up");
            });
            $(".samples-cart-sumary .icon-down").click(function () {
                $(this).toggleClass("icon-up-products");
            });

            $('.samples-cart-sumary  .icon-down').click(function () {
                $('.samples-cart-sumary .items-products').toggle();
            });
            $('.samples-cart-sumary  .item-title').click(function () {
                $('.samples-cart-sumary .items-products').toggle();
            });
        });


        $('.icon-down').click(function () {
            $('.address-container').toggle();
        });

        $('.btn-ship-her').click(function () {
            $('.checkbox-address').prop('checked', false);
            $('.address-container').removeClass('address-active');
            let id = $(this).data('id');
            $('#address_code').val(id);
            $.ajax({
                url: '/samples/cart/address/',
                type: 'post',
                data: {
                    customer_address_id: id
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                },
                error: function (xhr, status, errorThrown) {
                    console.log('error');
                }
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

        function errorAddress() {
            alertDialog({
                title: '',
                content: $.mage.__('Please select an address'),
                modalClass: 'modal-address-error',
                actions: {
                    always: function () {
                        console.log('');
                    }
                },
                buttons: [{
                    text: $.mage.__('Accept'),
                    class: 'action primary accept',
                    click: function () {
                        this.closeModal(true);
                    }
                }]
            });
        }
    }
);
