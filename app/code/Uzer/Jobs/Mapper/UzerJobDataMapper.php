<?php

namespace Uzer\Jobs\Mapper;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Jobs\Api\Data\UzerJobInterface;
use Uzer\Jobs\Api\Data\UzerJobInterfaceFactory;
use Uzer\Jobs\Model\UzerJob;

/**
 * Converts a collection of UzerJob entities to an array of data transfer objects.
 */
class UzerJobDataMapper
{
    /**
     * @var UzerJobInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param UzerJobInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        UzerJobInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|UzerJobInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var UzerJob $item */
        foreach ($collection->getItems() as $item) {
            /** @var UzerJobInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());
            $results[] = $entityDto;
        }
        return $results;
    }
}
