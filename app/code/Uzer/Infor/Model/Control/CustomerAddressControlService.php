<?php

namespace Uzer\Infor\Model\Control;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\AlreadyExistsException;
use Uzer\Infor\Model\CustomerAddressControl;
use Uzer\Infor\Model\CustomerAddressControlFactory as ModelFactory;
use Uzer\Infor\Model\ResourceModel\CustomerAddressControl\CollectionFactory;
use Uzer\Infor\Model\ResourceModel\CustomerAddressControlFactory;

class CustomerAddressControlService
{
    protected CustomerAddressControlFactory $resourceFactory;
    protected CollectionFactory $collectionFactory;
    protected ModelFactory $modelFactory;

    public function __construct(
        CustomerAddressControlFactory $resourceFactory,
        CollectionFactory             $collectionFactory,
        ModelFactory                  $modelFactory
    )
    {
        $this->resourceFactory = $resourceFactory;
        $this->collectionFactory = $collectionFactory;
        $this->modelFactory = $modelFactory;
    }

    /**
     * @param int $addressId
     * @param string $type
     * @return DataObject|CustomerAddressControl
     * @throws AlreadyExistsException
     */
    public function incrementAttempts(int $addressId, string $type = 'B'): CustomerAddressControl
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('address_id', $addressId);

        $item = $collection->getFirstItem();

        if ($item->getId()) {
            $item->setAttempts($item->getAttempts() + 1);
        } else {
            $item = $this->modelFactory->create();
            $item->setAddressId($addressId);
            $item->setAttempts(1);
            $item->setType($type);
        }
        $this->resourceFactory->create()->save($item);
        return $item;
    }

    public function markAsSynced(int $addressId): void
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('address_id', $addressId);

        $item = $collection->getFirstItem();

        if ($item->getId()) {
            $item->setIsSynced(true);
            $this->resourceFactory->create()->save($item);
        }
    }
}
