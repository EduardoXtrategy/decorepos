<?php

namespace Uzer\Samples\Api;

use Magento\Catalog\Model\Product;
use Uzer\Samples\Api\Data\SampleCartItemInterface;
use Uzer\Samples\Model\SampleCartItem;

interface CartInterface
{

    public function add(Product $product, string $parent, array $attributes, int $qty = 1);

    public function update(int $cartItemId, $qty);

    public function remove(int $cartItemId);

    /**
     * @param SampleCartItem $item
     * @throws \Exception
     */
    public function removeItem(SampleCartItem $item);

}
