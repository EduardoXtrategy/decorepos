<?php

declare(strict_types=1);

namespace Uzer\Infor\Observer;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Model\CustomerFactory as CustomerModelFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Uzer\Infor\Logger\OrderLogger;
use Uzer\Infor\Model\Api\ApiDispatcher;

/**
 * Dispatcher for the `sales_order_invoice_pay` event.
 */
class SalesOrderInvoicePayObserver implements ObserverInterface
{

    protected CustomerFactory $customerResourceFactory;
    protected CustomerModelFactory $customerFactory;
    protected ApiDispatcher $apiDispatcher;
    protected OrderLogger $orderLogger;

    public function __construct(
        CustomerFactory      $customerResourceFactory,
        CustomerModelFactory $customerFactory,
        ApiDispatcher        $apiDispatcher,
        OrderLogger          $orderLogger
    ) {
        $this->customerResourceFactory = $customerResourceFactory;
        $this->customerFactory = $customerFactory;
        $this->apiDispatcher = $apiDispatcher;
        $this->orderLogger = $orderLogger;
    }

    /**
     * Handle the `sales_order_invoice_pay` event.
     *
     * @param Observer $observer
     *
     * @return void
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
            $this->apiDispatcher->updateLineItems($order, 'F');
        } catch (\Exception $ex) {
            $this->orderLogger->error('Error dispatching order: ' . $order->getIncrementId() . '; ' . $ex->getMessage());
        } catch (GuzzleException $e) {
        }
    }
}
