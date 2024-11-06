<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Model\Cart;

class ShippingMethod extends \Magento\Quote\Model\Cart\ShippingMethod implements
    \Amasty\StorePickup\Api\Data\ShippingMethodInterface
{
    public function setComment($comment)
    {
        return $this->setData('comment', $comment);
    }

    public function getComment()
    {
        return $this->_get('comment');
    }
}
