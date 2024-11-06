require(['jquery', 'Magento_Ui/js/modal/modal', 'underscore', 'mage/translate'], function ($, modal, _, mage) {
    $(document).ready(function () {
        $('.amstockstatus-stockalert').html('');
        let options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Inventory Notification Activated'),
            buttons: [{
                text: $.mage.__('Close'),
                class: 'modal-close',
                click: function () {
                    this.closeModal();
                }
            }]
        };
        let items = $('#modal-inventory-stock-notification').size();
        if (items > 0) {
            modal(options, $('#modal-inventory-stock-notification'));
            $(".open-stock-notification-dialog").on('click', function () {
                $("#modal-inventory-stock-notification").modal("openModal");
                $(".modal-popup").addClass("modal-inventory-stock-notification");
            });
        }
    });

});
