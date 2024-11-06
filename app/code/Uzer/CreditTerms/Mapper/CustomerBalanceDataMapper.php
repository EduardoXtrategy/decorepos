<?php

namespace Uzer\CreditTerms\Mapper;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterface;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterfaceFactory;
use Uzer\CreditTerms\Model\CustomerBalance;

/**
 * Converts a collection of CustomerBalance entities to an array of data transfer objects.
 */
class CustomerBalanceDataMapper
{
    /**
     * @var CustomerBalanceInterfaceFactory
     */
    private CustomerBalanceInterfaceFactory $entityDtoFactory;

    /**
     * @param CustomerBalanceInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        CustomerBalanceInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|CustomerBalanceInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var CustomerBalance $item */
        foreach ($collection->getItems() as $item) {
            /** @var CustomerBalanceInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
