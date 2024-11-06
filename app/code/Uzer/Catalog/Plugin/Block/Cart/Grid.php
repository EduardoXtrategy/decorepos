<?php

namespace Uzer\Catalog\Plugin\Block\Cart;

class Grid
{

    public function getTemplate(\Magento\Checkout\Block\Cart\Grid $grid, $result): string
    {
        if (!$this->endsWith($result, 'form.phtml')) {
            return $result;
        }
        return 'Uzer_Catalog::checkout/cart.phtml';
    }

    public function endsWith($haystack, $needle): bool
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }
}
