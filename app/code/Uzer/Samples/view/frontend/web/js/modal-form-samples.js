/*------- Modal Samples Product -------*/

require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function ($, modal) {
        $(document).ready(function () {
            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: 'Sample Request',
                modalClass: 'modal-content-samples',
                buttons: []
            };
            let element = $('#modal-content-add-product');
            if (element.length > 0) {
                modal(options, element);
                $("#modal-btn").on('click', function () {
                    $("#modal-content-add-product").modal("openModal");
                });
            }
        });
    }
);
