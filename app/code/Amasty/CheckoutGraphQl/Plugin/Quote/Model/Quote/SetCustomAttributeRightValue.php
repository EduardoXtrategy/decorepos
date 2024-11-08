<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package One Step Checkout GraphQL (System)
 */

namespace Amasty\CheckoutGraphQl\Plugin\Quote\Model\Quote;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote;

class SetCustomAttributeRightValue
{
    /**
     * @param Quote $subject
     * @param AddressInterface|null $address
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetShippingAddress(Quote $subject, AddressInterface $address = null)
    {
        if ($address) {
            foreach ($address->getCustomAttributes() as $attribute) {
                if ($address->getData($attribute->getAttributeCode())) {
                    $attribute->setValue($address->getData($attribute->getAttributeCode()));
                }
            }
        }
    }
}
