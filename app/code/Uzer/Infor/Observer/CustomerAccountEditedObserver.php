<?php
declare(strict_types=1);

namespace Uzer\Infor\Observer;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Infor\Logger\Logger;
use Uzer\Infor\Model\Api\ApiDispatcher;

/**
 * Dispatcher for the `customer_account_edited` event.
 */
class CustomerAccountEditedObserver implements ObserverInterface
{

    protected CustomerFactory $customerFactory;
    protected StoreManagerInterface $storeManager;
    protected ApiDispatcher $apiDispatcher;
    protected Logger $logger;

    /**
     * @param CustomerFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param ApiDispatcher $apiDispatcher
     * @param Logger $logger
     */
    public function __construct(
        CustomerFactory       $customerFactory,
        StoreManagerInterface $storeManager,
        ApiDispatcher         $apiDispatcher,
        Logger                $logger
    )
    {
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->apiDispatcher = $apiDispatcher;
        $this->logger = $logger;
    }

    /**
     * Handle the `customer_account_edited` event.
     *
     * @param Observer $observer
     *
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        $customer = $this->customerFactory->create();
        $email = $observer->getEvent()->getData('email');
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();
        $customer = $customer->setStore($store)->loadByEmail($email);
        $address = $customer->getDefaultBillingAddress();
        $billingAddress = $address->getDataModel();
        try {
            if ($billingAddress) {
                $this->apiDispatcher->customer($customer, $billingAddress);
            }
        } catch (GuzzleException $e) {
            $this->logger->info('Error dispatch customer: ' . $customer->getId() . '; Error: ' . $e->getMessage());
        }
    }
}
