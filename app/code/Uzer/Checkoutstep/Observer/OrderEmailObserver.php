<?php

namespace Uzer\Checkoutstep\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Uzer\Customer\Model\CustomerCustomInfo;

class OrderEmailObserver implements ObserverInterface
{

    protected CustomerCustomInfo $customerCustomInfo;

    /**
     * @param CustomerCustomInfo $customerCustomInfo
     */
    public function __construct(CustomerCustomInfo $customerCustomInfo)
    {
        $this->customerCustomInfo = $customerCustomInfo;
    }


    public function execute(Observer $observer)
    {
        /** @var DataObject $transportObject */
        $transportObject = $observer->getData('transportObject');
        /** @var Order $order */
        $order = $transportObject->getData('order');
        $company = $this->customerCustomInfo->getByCustomerId($order->getCustomerId(), 'company_data');
        $transportObject->setData('company', $company);
        $transportObject->setData('purchaseOrder', $order->getData('purchase_order'));
        $transportObject->setData('purchase_order', $order->getData('purchase_order'));
    }
}
