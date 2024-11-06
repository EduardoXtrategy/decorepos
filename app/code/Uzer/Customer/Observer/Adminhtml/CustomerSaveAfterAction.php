<?php

namespace Uzer\Customer\Observer\Adminhtml;

use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModelFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerSaveAfterAction implements ObserverInterface
{

    protected RequestInterface $request;
    protected ResourceModelFactory $resourceModelCustomer;
    protected CustomerFactory $customerFactory;

    /**
     * @param RequestInterface $request
     * @param ResourceModelFactory $resourceModelCustomer
     * @param CustomerFactory $customerFactory
     */
    public function __construct(
        RequestInterface     $request,
        ResourceModelFactory $resourceModelCustomer,
        CustomerFactory      $customerFactory
    )
    {
        $this->request = $request;
        $this->resourceModelCustomer = $resourceModelCustomer;
        $this->customerFactory = $customerFactory;
    }


    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
        $customerInterface = $observer->getCustomer();
        $customer = $this->customerFactory->create();
        $resourceModel = $this->resourceModelCustomer->create();
        $resourceModel->load($customer, $customerInterface->getId());
        $customerData = $this->request->getParam('customer');
        $customer->setTitleData($customerData['title_data'] ?? '');
        $customer->setCompanyData($customerData['company_data'] ?? '');
        $resourceModel->saveAttribute($customer, 'company_data');
        $resourceModel->saveAttribute($customer, 'title_data');
    }
}
