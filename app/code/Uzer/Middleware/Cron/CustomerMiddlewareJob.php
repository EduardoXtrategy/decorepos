<?php


namespace Uzer\Middleware\Cron;


use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModelCustomer;
use Magento\Framework\Exception\LocalizedException;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;
use Uzer\Middleware\Model\CustomerIntegration;

class CustomerMiddlewareJob
{

    protected CollectionFactory $collectionFactory;
    protected ResourceModelCustomer $resourceModelCustomer;
    protected CustomerFactory $customerFactory;
    protected AddressRepositoryInterface $addressRepository;
    protected CustomerIntegration $customerIntegration;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceModelCustomer $resourceModelCustomer
     * @param CustomerFactory $customerFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param CustomerIntegration $customerIntegration
     */
    public function __construct(
        CollectionFactory          $collectionFactory,
        ResourceModelCustomer      $resourceModelCustomer,
        CustomerFactory            $customerFactory,
        AddressRepositoryInterface $addressRepository,
        CustomerIntegration        $customerIntegration
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->customerFactory = $customerFactory;
        $this->addressRepository = $addressRepository;
        $this->customerIntegration = $customerIntegration;
        $this->resourceModelCustomer = $resourceModelCustomer;
    }


    /**
     * Cronjob Description
     *
     * @return void
     * @throws LocalizedException
     */
    public function execute(): void
    {
        /** @var InformationBusiness[] $items */
        $items = $this->collectionFactory->create()->addFieldToFilter('saved_middleware', array('eq' => false))->load()->getItems();
        foreach ($items as $item) {
            $customer = $this->customerFactory->create();
            $this->resourceModelCustomer->create()->load($customer, $item->getCustomersId());
            $address = $this->addressRepository->getById($item->getAddressesId());
            $this->customerIntegration->save($customer, $address, $item);
        }
    }
}
