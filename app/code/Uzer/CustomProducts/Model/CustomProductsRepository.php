<?php

namespace Uzer\CustomProducts\Model;

use Uzer\CustomProducts\Api\CustomProductsRepositoryInterface;
use Uzer\CustomProducts\Api\Data\CustomProductsInterface;
use Uzer\CustomProducts\Model\CustomerProductFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProductFactory as ResourceModelFactory;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProduct\CollectionFactory;

class CustomProductsRepository implements CustomProductsRepositoryInterface
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

    /**
     * @inheritDoc
     */
    public function saveByCustomerId(CustomProductsInterface $items): CustomProductsInterface
    {
        return $items;
    }

    public function deleteByCustomerId(CustomProductsInterface $items): CustomProductsInterface
    {
        return $items;
    }
}
