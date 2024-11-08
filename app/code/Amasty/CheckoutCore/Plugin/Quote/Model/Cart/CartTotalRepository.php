<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package One Step Checkout Core for Magento 2
 */

namespace Amasty\CheckoutCore\Plugin\Quote\Model\Cart;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Registry;

class CartTotalRepository
{
    public const REGISTRY_IGNORE_EXTENSION_ATTRIBUTES_KEY = 'amasty_checkout_ignore_extension_attributes';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    public function __construct(
        Registry $registry,
        ProductMetadataInterface $productMetadata
    ) {
        $this->registry = $registry;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Fix Magento bug on checkout API
     * @see \Amasty\CheckoutCore\Plugin\Framework\Api\DataObjectHelperPlugin::beforePopulateWithArray
     *
     * @param \Magento\Quote\Model\Cart\CartTotalRepository $subject
     * @param int|string                                    $cartId
     *
     * @return array
     */
    public function beforeGet(\Magento\Quote\Model\Cart\CartTotalRepository $subject, $cartId)
    {
        if (version_compare($this->productMetadata->getVersion(), '2.2.4', '<')) {
            $this->registry->register(self::REGISTRY_IGNORE_EXTENSION_ATTRIBUTES_KEY, true, true);
        }

        return [$cartId];
    }

    /**
     * Fix Magento bug on checkout API
     * @see \Amasty\CheckoutCore\Plugin\Framework\Api\DataObjectHelperPlugin::beforePopulateWithArray
     *
     * @param \Magento\Quote\Model\Cart\CartTotalRepository $subject
     * @param \Magento\Quote\Model\Cart\Totals              $quoteTotals
     *
     * @return \Magento\Quote\Model\Cart\Totals
     */
    public function afterGet(\Magento\Quote\Model\Cart\CartTotalRepository $subject, $quoteTotals)
    {
        $this->registry->unregister(self::REGISTRY_IGNORE_EXTENSION_ATTRIBUTES_KEY);

        return $quoteTotals;
    }
}
