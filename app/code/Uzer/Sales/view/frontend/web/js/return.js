require([
    'jquery',
    'underscore',
    'mage/translate',
    'salesReturnProducts'
], function (
    $,
    _,
    mage,
    salesReturnProducts
) {
    $(document).ready(function () {
        $('.return').click(function () {
            $.mage.salesReturnProducts.prototype.openPopup();
        });
        $('#return-form').submit(function (e) {
            e.preventDefault();
            let url = $(this).prop('action');
            if ($(this).valid()) {
                $.mage.salesReturnProducts.prototype.sendForm(this, url);
            }
        })
    });
});


//Input file configurations


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
                if (file && (file.type === 'image/jpeg' || file.type === 'image/png')) {
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


require(['jquery'], function ($) {
    $(document).ready(function () {
        $('input[type="file"]').change(function () {
            var fileSize = this.files[0].size;
            if (fileSize > 5000000) {
                alert($t('File size must be less than 5 MB'));
                $(this).val('');
                $(this).closest('.box').find('label[for="picture"] > span').text($t('Upload picture'));
            }
        });
    });
});