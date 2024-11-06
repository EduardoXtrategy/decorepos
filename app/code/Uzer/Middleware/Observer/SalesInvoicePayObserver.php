<?php

namespace Uzer\Middleware\Observer;

use Magento\Customer\Model\CustomerFactory as CustomerModelFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerFactory;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order\Invoice;
use Uzer\Core\Api\OrderIntegrationInterface;
use Uzer\Middleware\Logger\Logger;

class SalesInvoicePayObserver implements \Magento\Framework\Event\ObserverInterface
{

    protected OrderIntegrationInterface $orderIntegration;
    protected Logger $logger;
    protected CustomerFactory $customerResourceFactory;
    protected CustomerModelFactory $customerFactory;

    /**
     * @param OrderIntegrationInterface $orderIntegration
     * @param Logger $logger
     * @param CustomerFactory $customerResourceFactory
     * @param CustomerModelFactory $customerFactory
     */
    public function __construct(OrderIntegrationInterface $orderIntegration, Logger $logger, CustomerFactory $customerResourceFactory, CustomerModelFactory $customerFactory)
    {
        $this->orderIntegration = $orderIntegration;
        $this->logger = $logger;
        $this->customerResourceFactory = $customerResourceFactory;
        $this->customerFactory = $customerFactory;
    }


    /**
     * @description Dispatch order to middleware when invoice is created
     *
     * @inheritDoc
     */
    public function execute(Observer $observer): void
    {
        /** @var Invoice $invoice */
        $invoice = $observer->getData('invoice');
        $order = $invoice->getOrder();
        try {
            $customer = $this->customerFactory->create();
            $this->customerResourceFactory->create()->load($customer, $order->getCustomerId());
            $order->setCustomer($customer);
            $this->orderIntegration->save($order);
        } catch (\Exception $ex) {
            $this->logger->info('Error dispatching order: ' . $order->getIncrementId() . '; ' . $ex->getMessage());
        }
    }
}
