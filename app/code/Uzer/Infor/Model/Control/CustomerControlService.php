<?php

namespace Uzer\Infor\Model\Control;

use Magento\Framework\Exception\AlreadyExistsException;
use Uzer\Infor\Model\CustomerControl;
use Uzer\Infor\Model\CustomerControlFactory as ModelFactory;
use Uzer\Infor\Model\ResourceModel\CustomerControl\CollectionFactory;
use Uzer\Infor\Model\ResourceModel\CustomerControlFactory;

class CustomerControlService
{


    protected CustomerControlFactory $resourceFactory;
    protected CollectionFactory $collectionFactory;
    protected ModelFactory $modelFactory;

    /**
     * @param CustomerControlFactory $resourceFactory
     * @param CollectionFactory $collectionFactory
     * @param ModelFactory $modelFactory
     */
    public function __construct(
        CustomerControlFactory $resourceFactory,
        CollectionFactory      $collectionFactory,
        ModelFactory           $modelFactory
    )
    {
        $this->resourceFactory = $resourceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->modelFactory = $modelFactory;
    }


    public function incrementAttempts(int $customerId): void
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId);

        /** @var CustomerControl $item */
        $item = $collection->getFirstItem();

        if ($item->getId()) {
            $item->setAttempts($item->getAttempts() + 1);
        } else {
            $item = $this->modelFactory->create();
            $item->setCustomerId($customerId);
            $item->setAttempts(1);
        }

        $this->resourceFactory->create()->save($item);
    }

    /**
     * @param int $customerId
     * @return void
     * @throws AlreadyExistsException
     */
    public function markAsSynced(int $customerId): void
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId);

        /** @var CustomerControl $item */
        $item = $collection->getFirstItem();

        if ($item->getId()) {
            $item->setSaved(true);
            $this->resourceFactory->create()->save($item);
        }
    }

    public function isSynced(int $customerId): bool
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId);

        /** @var CustomerControl $item */
        $item = $collection->getFirstItem();

        return (bool)$item->getSaved();
    }


}
