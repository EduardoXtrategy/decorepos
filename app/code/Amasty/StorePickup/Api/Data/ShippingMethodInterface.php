<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Api\Data;

/**
 * Interface ShippingMethodInterface
 * @api
 */
interface ShippingMethodInterface extends \Magento\Quote\Api\Data\ShippingMethodInterface
{
    /**
     * Sets the shipping carrier comment.
     *
     * @param string $comment
     * @return \Amasty\StorePickup\Api\Data\ShippingMethodInterface
     */
    public function setComment($comment);

    /**
     * Sets the shipping carrier comment.
     *
     * @return \Amasty\StorePickup\Api\Data\ShippingMethodInterface
     */
    public function getComment();
}
