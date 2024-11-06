<?php

namespace Uzer\Catalog\Block\Cart\Item\Renderer\Actions;

use Magento\Checkout\Block\Cart\Item\Renderer\Actions\Generic;
use Magento\Checkout\Helper\Cart;
use Magento\Framework\View\Element\Template\Context;
use Magento\Wishlist\Helper\Data;

class Actions extends Generic
{
    /**
     * @var Cart
     */
    protected Cart $cartHelper;
    protected Data $wishlistHelper;

    public function __construct(
        Context $context,
        Cart    $cartHelper,
        Data    $wishlistHelper,
        array   $data = []
    )
    {
        parent::__construct($context, $data);
        $this->cartHelper = $cartHelper;
        $this->wishlistHelper = $wishlistHelper;
    }

    /**
     * Get delete item POST JSON
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getDeletePostJson(): string
    {
        return $this->cartHelper->getDeletePostJson($this->getItem());
    }

    /**
     * Check whether "add to wishlist" button is allowed in cart
     *
     * @return bool
     */
    public function isAllowInCart(): bool
    {
        return $this->wishlistHelper->isAllowInCart();
    }

    /**
     * Get JSON POST params for moving from cart
     *
     * @return string
     */
    public function getMoveFromCartParams(): string
    {
        return $this->wishlistHelper->getMoveFromCartParams($this->getItem()->getId());
    }
}
