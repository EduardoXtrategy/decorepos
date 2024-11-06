require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ], function ($, modal) {
        $(document).ready(function () {
            $('.btn-update-cart').hide();
            $('.btn-sample-dismiss').click(function () {
                let dataItem = $(this).data('item');
                let inputID = $(`#item-${dataItem}`);
                let currentVal = parseInt(inputID.val());
                if (currentVal > 1) {
                    inputID.val(currentVal - 1);
                }
                let dataUpdate = $(this).data('update');
                $(`#update-cart-` + dataUpdate).show();
            });
            $('.btn-sample-plus').click(function () {
                let dataItem = $(this).data('item');
                let inputID = $(`#item-${dataItem}`);
                let currentVal = parseInt(inputID.val());
                if (currentVal < 50) {
                    inputID.val(currentVal + 1);
                }
                let dataUpdate = $(this).data('update');
                $(`#update-cart-` + dataUpdate).show();
            });
        });
    }
);
