<?php

namespace Uzer\CreditTerms\Observer\Adminhtml;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterface;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterfaceFactory;
use Uzer\CreditTerms\Api\SaveCustomerBalanceInterface;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance\CollectionFactory;

class AfterSaveCustomer implements ObserverInterface
{
    protected CollectionFactory $collectionFactory;
    protected CustomerBalanceInterfaceFactory $customerBalanceFactory;
    protected SaveCustomerBalanceInterface $saveCustomerBalance;
    protected RequestInterface $request;

    /**
     * @param CollectionFactory $collectionFactory
     * @param CustomerBalanceInterfaceFactory $customerBalanceFactory
     * @param SaveCustomerBalanceInterface $saveCustomerBalance
     * @param RequestInterface $request
     */
    public function __construct(
        CollectionFactory               $collectionFactory,
        CustomerBalanceInterfaceFactory $customerBalanceFactory,
        SaveCustomerBalanceInterface    $saveCustomerBalance,
        RequestInterface                $request
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->customerBalanceFactory = $customerBalanceFactory;
        $this->saveCustomerBalance = $saveCustomerBalance;
        $this->request = $request;
    }


    /**
     * @throws CouldNotSaveException
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getCustomer();
        $model = $this->collectionFactory->create()
            ->addFieldToFilter('customers_id', array('eq' => $customer->getId()))
            ->load()
            ->getFirstItem();
        $creditTermsRequest = $this->request->getParam('creditterms_global_terms');
        if (!$model->hasData()) {
            $model = $this->customerBalanceFactory->create();
        }
        $model->setCustomersId($customer->getId());
        $model->setValue($creditTermsRequest['global_credit_terms'] ?? 0);
        $this->saveCustomerBalance->execute($model);
    }
}
