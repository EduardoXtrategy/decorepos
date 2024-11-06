require(['jquery', 'underscore', 'mage/translate', 'Magento_Catalog/js/price-utils', 'removeProductsCart'],
    function (
        $,
        _,
        mage,
        priceUtils,
        removeProductsCart) {
        let qty = 0;
        let boxSize = 0;
        let availableBoxes = 0;
        let id = null;
        let currentQty = 0;
        let currentQtyBoxes = 0;
        let hasPartialBox = false;
        let partialBox = 0;
        $(document).ready(function () {
            $('.qty-down').click(function () {
                let data = $(this).data('product');
                initialize(data);
                decrease();
                showUpdateButton(data);
            });
            $('.qty-up').click(function () {
                let data = $(this).data('product');
                initialize(data);
                increase();
                showUpdateButton();
            });
            $('.action-delete').click(function () {
                console.log('action delete');
                let dataDelete = $(this).data('delete');
                let dataWishlist = $(this).data('wishlist');
                $.mage.removeOrAddToWish.prototype.openDialog(dataDelete, dataWishlist);
            });
        });

        function showUpdateButton(data) {
            let json = JSON.parse(toJson);
            let id = json.id;
            $('div').find(`[data-item-update="${id}"]`).show();
        }

        function initialize(data) {
            toJson = data.replaceAll("'", '"');
            let json = JSON.parse(toJson);
            qty = json.qty;
            boxSize = json.box_size;
            availableBoxes = json.available_boxes;
            id = json.id;
            currentQty = parseInt($(getInputQty()).val());
            currentQtyBoxes = parseInt($(getInputBoxes()).val());
            hasPartialBox = json.has_partial_box;
            partialBox = json['partial_box_qty'] ?? 0;
        }

        function getInputQty() {
            return `#cart-${id}-qty`;
        }

        function getInputBoxes() {
            let item = `#item-${id}-boxes`;
            return item;
        }

        function getLabelQty() {
            let item = `#item-${id}-qty`;
            return item;
        }

        function getInputPartial() {
            let item = `#item-${id}-partial`;
            return item;
        }

        function increase() {
            currentQtyBoxes = currentQtyBoxes + 1;
            let qtyCalculated = boxSize * currentQtyBoxes;
            if (qtyCalculated <= qty) {
                $(getInputBoxes()).val(currentQtyBoxes);
                $(getLabelQty()).html(qtyCalculated);
                $(getInputQty()).val(qtyCalculated);
            }
        }

        function decrease() {
            console.log('decrease', currentQtyBoxes);
            if (currentQtyBoxes > 1) {
                console.log(currentQty);
                let qtyCalculated = 0;
                currentQtyBoxes = currentQtyBoxes - 1;
                if (hasPartialBox) {
                    console.log(getInputPartial());
                    $(getInputPartial()).val('1');
                    qtyCalculated = currentQty - partialBox;
                } else {
                    qtyCalculated = boxSize * currentQtyBoxes;
                }
                console.log(qtyCalculated, currentQtyBoxes);
                $(getInputBoxes()).val(currentQtyBoxes);
                $(getLabelQty()).html(qtyCalculated);
                $(getInputQty()).val(qtyCalculated);
            }
        }
    });
