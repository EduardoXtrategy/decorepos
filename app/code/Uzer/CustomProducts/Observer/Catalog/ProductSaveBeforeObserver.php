<?php

namespace Uzer\CustomProducts\Observer\Catalog;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Observes the `catalog_product_save_before` event.
 */
class ProductSaveBeforeObserver implements ObserverInterface
{
    /**
     * Observer for catalog_product_save_before.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var \Magento\Catalog\Model\Product|\Magento\Catalog\Api\Data\ProductInterface $product */
        $product = $observer->getEvent()->getProduct();
        $custom = $product->getData('custom');
        if (!is_null($custom)) {
            $product->setData('is_custom', $custom);
        }
    }
}
