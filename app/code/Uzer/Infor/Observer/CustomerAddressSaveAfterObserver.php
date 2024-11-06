<?php
declare(strict_types=1);

namespace Uzer\Infor\Observer;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Uzer\Infor\Logger\Logger;
use Uzer\Infor\Model\Api\ApiDispatcher;
use Uzer\Infor\Model\Control\CustomerControlService;

/**
 * Dispatcher for the `customer_address_save_after` event.
 */
class CustomerAddressSaveAfterObserver implements ObserverInterface
{
    protected ApiDispatcher $apiDispatcher;
    protected CustomerControlService $customerControlService;
    protected Logger $logger;

    /**
     * @param ApiDispatcher $apiDispatcher
     * @param CustomerControlService $customerControlService
     * @param Logger $logger
     */
    public function __construct(
        ApiDispatcher          $apiDispatcher,
        CustomerControlService $customerControlService,
        Logger                 $logger
    )
    {
        $this->apiDispatcher = $apiDispatcher;
        $this->customerControlService = $customerControlService;
        $this->logger = $logger;
    }


    /**
     * Handle the `customer_address_save_after` event.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        try {
            /** @var \Magento\Customer\Model\Address $address */
            $address = $observer->getEvent()->getData('customer_address');
            $customer = $address->getCustomer();
            if (!$this->customerControlService->isSynced((int)$customer->getId())) {
                return;
            }
            $dataModel = $address->getDataModel();
            if ($dataModel) {
                $this->apiDispatcher->customer($customer, $dataModel);
            }
        } catch (GuzzleException|\Exception $ex) {
            $this->logger->error('Error saving updating address: ' . $ex->getMessage());
        }
    }
}
