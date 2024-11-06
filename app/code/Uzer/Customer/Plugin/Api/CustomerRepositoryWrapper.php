<?php

namespace Uzer\Customer\Plugin\Api;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerSearchResultsInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModelCustomerFactory;
use Uzer\Customer\Model\CustomerCustomInfo;
use Uzer\Customer\Model\InformationBusinessFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModel;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory as CollectionFactory;

class CustomerRepositoryWrapper
{

    protected InformationBusinessFactory $informationBusinessFactory;
    protected ResourceModel $resourceModel;
    protected CollectionFactory $collectionFactory;
    protected CustomerFactory $_customerFactory;
    protected ResourceModelCustomerFactory $resourceModelCustomer;
    protected CustomerCustomInfo $customerCustomInfo;
    protected CustomerExtensionInterface $extensionAttributes;

    /**
     * @param InformationBusinessFactory $informationBusinessFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param CustomerFactory $_customerFactory
     * @param ResourceModelCustomerFactory $resourceModelCustomer
     * @param CustomerCustomInfo $customerCustomInfo
     */
    public function __construct(
        InformationBusinessFactory   $informationBusinessFactory,
        ResourceModel                $resourceModel,
        CollectionFactory            $collectionFactory,
        CustomerFactory              $_customerFactory,
        ResourceModelCustomerFactory $resourceModelCustomer,
        CustomerCustomInfo           $customerCustomInfo
    )
    {
        $this->informationBusinessFactory = $informationBusinessFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->_customerFactory = $_customerFactory;
        $this->resourceModelCustomer = $resourceModelCustomer;
        $this->customerCustomInfo = $customerCustomInfo;
    }

    public function afterGet(CustomerRepositoryInterface $subject, CustomerInterface $customer): CustomerInterface
    {
        $company = $this->customerCustomInfo->getByData($customer, 'company_data');
        $title = $this->customerCustomInfo->getByData($customer, 'title_data');
        $customer->getExtensionAttributes()->setCompany($company);
        $customer->getExtensionAttributes()->setTitle($title);
        return $customer;
    }

    public function afterGetById(CustomerRepositoryInterface $subject, CustomerInterface $customer): CustomerInterface
    {
        $company = $this->customerCustomInfo->getByData($customer, 'company_data');
        $title = $this->customerCustomInfo->getByData($customer, 'title_data');
        $customer->getExtensionAttributes()->setCompany($company);
        $customer->getExtensionAttributes()->setTitle($title);
        return $customer;
    }

    public function afterGetList(CustomerRepositoryInterface $subject, CustomerSearchResultsInterface $searchCriteria): CustomerSearchResultsInterface
    {
        foreach ($searchCriteria->getItems() as $entity) {
            $this->afterGet($subject, $entity);
        }
        return $searchCriteria;
    }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @param $passwordHash
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(CustomerRepositoryInterface $subject, CustomerInterface $result, $passwordHash = null): array
    {
        $this->extensionAttributes = $result->getExtensionAttributes();
        return [$result, $passwordHash];
    }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(CustomerRepositoryInterface $subject, CustomerInterface $result): CustomerInterface
    {
        $this->saveAttributes($result);
        if (count($result->getAddresses()) > 0) {
            $previousItem = $this->collectionFactory->create()->addFieldToFilter('customers_id', array('eq' => $result->getId()))->load()->getFirstItem();
            if (!$previousItem->hasData()) {
                try {
                    $informationBusiness = $this->informationBusinessFactory->create();
                    $informationBusiness->setCustomersId($result->getId());
                    $informationBusiness->setAccountEmail($result->getEmail());
                    $informationBusiness->setAddressesId($result->getAddresses()[0]->getId());
                    $this->resourceModel->save($informationBusiness);
                } catch (\Exception $ex) {

                }
            }
        }
        return $result;
    }

    private function saveAttributes(CustomerInterface $result)
    {
        $customer = $this->_customerFactory->create();
        $resourceModel = $this->resourceModelCustomer->create();
        $resourceModel->load($customer, $result->getId());
        $title = $this->extensionAttributes->getTitle();
        $company = $this->extensionAttributes->getCompany();
        if (!empty($title)) {
            $customer->setTitleData($title);
            $resourceModel->saveAttribute($customer, 'title_data');
        }
        if (!empty($company)) {
            $customer->setCompanyData($company);
            $resourceModel->saveAttribute($customer, 'company_data');
        }
    }
}
