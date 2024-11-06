<?php

namespace Uzer\CustomProducts\Plugin\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Uzer\CustomProducts\Model\CustomerProduct;
use Uzer\CustomProducts\Model\CustomerProductFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProductFactory as ResourceModelFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProduct\CollectionFactory;

class CustomProduct
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
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        ProductInterface                                $entity
    ): ProductInterface
    {
        $product = $entity;
        $items = $this->collectionFactory->create()->addFieldToFilter('sku', array('eq' => $entity->getSku()))->load();
        /** @var CustomerProduct $item */
        $customers = [];
        foreach ($items as $item) {
            $customers[] = $item->getCustomerId();
        }
        $product->getExtensionAttributes()->setCustomers($customers);
        return $product;
    }

    public function afterGetList(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        ProductSearchResultsInterface                   $searchCriteria): ProductSearchResultsInterface
    {
        $products = [];
        foreach ($searchCriteria->getItems() as $entity) {
            $this->afterGet($subject, $entity);
            $products[] = $entity;
        }
        $searchCriteria->setItems($products);
        return $searchCriteria;
    }

    /**
     * @throws AlreadyExistsException
     * @throws \Exception
     */
    public function beforeSave(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        ProductInterface                                $product,
        bool                                            $saveOptions = false
    )
    {
        $customers = $product->getExtensionAttributes()->getCustomers();
        if (!$customers) {
            return [$product];
        }
        $resourceModel = $this->resourceModelFactory->create();
        /** @var CustomerProduct[] $previousItems */
        $previousItems = $this->collectionFactory->create()->addFieldToFilter('sku', array('eq' => $product->getSku()))->load();
        $items = [];
        foreach ($previousItems as $previousItem) {
            $items[$previousItem->getCustomerId()] = $previousItem;
        }
        $customers = array_unique($customers);
        foreach ($customers as $key => $customer) {
            if (!isset($items[$customer])) {
                $customerProduct = $this->customerProductFactory->create();
                $customerProduct->setCustomerId($customer);
                $customerProduct->setSku($product->getSku());
                $resourceModel->save($customerProduct);
            }
        }
        return [$product];
    }
}
