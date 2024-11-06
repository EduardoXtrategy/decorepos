<?php
declare(strict_types=1);

namespace Uzer\Infor\Observer;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Uzer\Infor\Logger\Logger;
use Uzer\Infor\Model\Api\ApiDispatcher;

/**
 * Dispatcher for the `bussines_information_success` event.
 */
class BussinesInformationSuccessObserver implements ObserverInterface
{

    protected ApiDispatcher $apiDispatcher;
    protected Logger $logger;

    /**
     * @param ApiDispatcher $apiDispatcher
     * @param Logger $logger
     */
    public function __construct(ApiDispatcher $apiDispatcher, Logger $logger)
    {
        $this->apiDispatcher = $apiDispatcher;
        $this->logger = $logger;
    }


    /**
     * Handle the `bussines_information_success` event.
     *
     * @param Observer $observer
     *
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer): void
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getData('customer');
        /** @var AddressInterface $address */
        $address = $observer->getData('address');
        try {
            $this->apiDispatcher->customer($customer, $address, 'B');
            $this->apiDispatcher->customer($customer, $address, 'S');
            $this->apiDispatcher->customerDocuments($customer);
        } catch (GuzzleException $e) {
            $this->logger->info('Error dispatch customer: ' . $customer->getId() . '; Error: ' . $e->getMessage());
        }
    }
}
