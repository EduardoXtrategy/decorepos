<?php

namespace Uzer\Checkoutstep\Observer;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Uzer\Checkoutstep\Model\PurchaseOrder;
use Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrder\Collection;

class SalesModelServiceQuoteSubmitBefore implements ObserverInterface
{

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getData('order');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        $order->setData('purchase_order', $quote->getData('purchase_order'));

        $collection = ObjectManager::getInstance()->create(Collection::class);
        /** @var PurchaseOrder $purchaseOrder */
        $purchaseOrder = $collection->addQuoteIdToFilter($quote->getId())->load()->getFirstItem();
        if ($purchaseOrder->hasData()) {
            $quote->setData('purchase_order', $purchaseOrder->getPoNumber());
            $order->setData('purchase_order', $purchaseOrder->getPoNumber());
            if ($quote->getPayment()) {
                $order->getPayment()->setPoNumber($purchaseOrder->getPoNumber());
            }
            if ($order->getPayment()) {
                $order->getPayment()->setPoNumber($purchaseOrder->getPoNumber());
            }
        }
    }
}
