<?php

namespace Uzer\CustomProducts\Command\CustomerProduct;

use Uzer\CustomProducts\Model\CustomerProduct;
use Uzer\CustomProducts\Model\CustomerProductMap;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomerFactory as ResourceModelFactory;
use Uzer\CustomProducts\Model\CategoryCustomerFactory as ModelFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProduct\CollectionFactory;

class CustomProductByCustomer
{

    protected CollectionFactory $collectionFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected ModelFactory $modelFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param ModelFactory $modelFactory
     */
    public function __construct(CollectionFactory $collectionFactory, ResourceModelFactory $resourceModelFactory, ModelFactory $modelFactory)
    {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->modelFactory = $modelFactory;
    }


    public function getProducts(): array
    {
        /** @var CustomerProductMap[] $customerProductsMapItem */
        $customerProductsMapItem = [];
        /** @var CustomerProduct[] $items */
        $items = $this->collectionFactory->create()->load()->getItems();
        foreach ($items as $item) {
            $customerProductsMap = $customerProductsMapItem[$item->getCustomerId()] ?? new CustomerProductMap();
            $customerProductsMap->setCustomerId($item->getCustomerId());
            $customerProductsMap->addProduct($item->getSku());
            $customerProductsMap->setPosition($item->getId());
            $customerProductsMapItem[$item->getCustomerId()] = $customerProductsMap;
        }
        return $customerProductsMapItem;
    }

}
