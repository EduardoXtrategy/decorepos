<?php

namespace Uzer\Middleware\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Uzer\Middleware\Model\CustomerIntegration;

class CustomerSave implements ObserverInterface
{

    protected CustomerIntegration $customerIntegration;

    /**
     * @param CustomerIntegration $customerIntegration
     */
    public function __construct(CustomerIntegration $customerIntegration)
    {
        $this->customerIntegration = $customerIntegration;
    }


    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getData('customer');
        $address = $observer->getData('address');
        $informationBusiness = $observer->getData('informationBusiness');
        $this->customerIntegration->save($customer, $address, $informationBusiness);        
    }
    
}
