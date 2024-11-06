<?php
declare(strict_types=1);

namespace Uzer\Infor\Observer;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Model\CustomerFactory as CustomerModelFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Uzer\Infor\Logger\OrderLogger;
use Uzer\Infor\Model\Api\ApiDispatcher;

/**
 * Dispatcher for the `checkout_submit_all_after` event.
 */
class CheckoutSubmitAllAfterObserver implements ObserverInterface
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
    )
    {
        $this->customerResourceFactory = $customerResourceFactory;
        $this->customerFactory = $customerFactory;
        $this->apiDispatcher = $apiDispatcher;
        $this->orderLogger = $orderLogger;
    }

    /**
     * Handle the `checkout_submit_all_after` event.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getData('order');
        $customer = $this->customerFactory->create();
        try {
            $this->customerResourceFactory->create()->load($customer, $order->getCustomerId());
            $order->setCustomer($customer);
            $this->apiDispatcher->orderV2($order);
            if ($order->getPayment()->getMethod() == 'terms') {
                $this->apiDispatcher->updateLineItems($order, 'F');
            }
        } catch (Exception|GuzzleException $ex) {
            $this->orderLogger->error('Error dispatching order: ' . $order->getIncrementId() . '; ' . $ex->getMessage());
        }
    }
}
