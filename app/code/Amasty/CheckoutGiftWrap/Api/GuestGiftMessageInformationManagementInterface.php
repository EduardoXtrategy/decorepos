<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package One Step Checkout Gift Wrap for Magento 2 (System)
 */

namespace Amasty\CheckoutGiftWrap\Api;

interface GuestGiftMessageInformationManagementInterface
{
    /**
     * @param string $cartId
     * @param mixed $giftMessage
     *
     * @return bool
     */
    public function update($cartId, $giftMessage): bool;
}
