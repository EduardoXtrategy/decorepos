<?php

namespace Uzer\CustomProducts\Plugin\Api;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerSearchResultsInterface;
use Uzer\CustomProducts\Model\CustomerProduct;
use Uzer\CustomProducts\Model\CustomerProductFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProduct\CollectionFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProductFactory as ResourceModelFactory;

class CustomCustomer
{

    protected CollectionFactory $collectionFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected CustomerProductFactory $customerProductFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param CustomerProductFactory $customerProductFactory
     */
    public function __construct(
        CollectionFactory      $collectionFactory,
        ResourceModelFactory   $resourceModelFactory,
        CustomerProductFactory $customerProductFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->customerProductFactory = $customerProductFactory;
    }

    public function afterGet(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface           $customer
    ): CustomerInterface
    {
        $items = $this->collectionFactory->create()->addFieldToFilter('customers_id', array('eq' => $customer->getId()))->load();
        /** @var CustomerProduct $item */
        $products = [];
        foreach ($items as $item) {
            $products[] = $item->getSku();
        }
        $customer->getExtensionAttributes()->setProducts($products);
        return $customer;
    }

    public function afterGetById(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface           $customer
    ): CustomerInterface
    {
        return $this->afterGet($customerRepository, $customer);
    }

    public function afterGetList(
        CustomerRepositoryInterface    $subject,
        CustomerSearchResultsInterface $searchCriteria
    ): CustomerSearchResultsInterface
    {
        $products = [];
        foreach ($searchCriteria->getItems() as $entity) {
            $this->afterGet($subject, $entity);
            $products[] = $entity;
        }
        $searchCriteria->setItems($products);
        return $searchCriteria;
    }

}
