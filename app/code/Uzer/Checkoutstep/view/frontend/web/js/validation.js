define([
    'jquery',
    'mage/validation'
], function ($) {
    'use strict';

    alert('Validation JS is loaded.'); // Alert indicating that the JS is loaded

    $.validator.addMethod(
        'validate-max-length',
        function (value) {
            return value.length <= 10;
        },
        $.mage.__('Please enter no more than 10 characters.')
    );

    $.validator.addMethod(
        'validate-min-length',
        function (value) {
            return value.length >= 10;
        },
        $.mage.__('Please enter at least 10 characters.')
    );

    return function () {
        $(document).ready(function () {
            $('[name="paymentMethod.additional_data.purchaseOrder"]').rules('add', {
                'validate-max-length': true,
                'validate-min-length': true
            });
        });
    };
});
