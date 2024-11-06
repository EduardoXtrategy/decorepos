<?php

namespace Uzer\Middleware\Observer;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModelCustomer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Customer\Model\InformationBusinessFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModelInformation;
use Uzer\Middleware\Logger\Logger;
use Uzer\Middleware\Model\CustomerIntegration;

/**
 * Observes the `customer_account_edited` event.
 */
class CustomerAccountEditedObserver implements ObserverInterface
{

    protected ResourceModelCustomer $resourceModel;
    protected CustomerFactory $customerFactory;
    protected CustomerIntegration $customerIntegration;
    protected ResourceModelInformation $informationBusiness;
    protected InformationBusinessFactory $informationBusinessFactory;
    protected AddressRepositoryInterface $addressRepository;
    protected StoreManagerInterface $storeManager;
    protected Logger $logger;

    /**
     * @param ResourceModelCustomer $resourceModel
     * @param CustomerFactory $customerFactory
     * @param CustomerIntegration $customerIntegration
     * @param ResourceModelInformation $informationBusiness
     * @param InformationBusinessFactory $informationBusinessFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param StoreManagerInterface $storeManager
     * @param Logger $logger
     */
    public function __construct(
        ResourceModelCustomer      $resourceModel,
        CustomerFactory            $customerFactory,
        CustomerIntegration        $customerIntegration,
        ResourceModelInformation   $informationBusiness,
        InformationBusinessFactory $informationBusinessFactory,
        AddressRepositoryInterface $addressRepository,
        StoreManagerInterface      $storeManager,
        Logger                     $logger
    )
    {
        $this->resourceModel = $resourceModel;
        $this->customerFactory = $customerFactory;
        $this->customerIntegration = $customerIntegration;
        $this->informationBusiness = $informationBusiness;
        $this->informationBusinessFactory = $informationBusinessFactory;
        $this->addressRepository = $addressRepository;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }


    /**
     * Observer for customer_account_edited.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            $customer = $this->customerFactory->create();
            $email = $observer->getEvent()->getData('email');
            $customer = $customer->setStore($this->storeManager->getStore())->loadByEmail($email);
            $informationBusiness = $this->informationBusinessFactory->create();
            $this->informationBusiness->loadByCustomerId($informationBusiness, $customer->getId());
            if ($informationBusiness->hasData()) {
                $address = $this->addressRepository->getById($informationBusiness->getAddressesId());
                $this->customerIntegration->save($customer, $address, $informationBusiness);
            }
        } catch (\Exception $ex) {
            $this->logger->info('Customer not found: ' . $ex->getMessage());
        }

    }
}
