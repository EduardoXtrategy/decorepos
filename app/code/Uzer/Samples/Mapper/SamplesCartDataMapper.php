<?php

namespace Uzer\Samples\Mapper;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Samples\Api\Data\SamplesCartInterface;
use Uzer\Samples\Api\Data\SamplesCartInterfaceFactory;
use Uzer\Samples\Model\SamplesCart;

/**
 * Converts a collection of SamplesCart entities to an array of data transfer objects.
 */
class SamplesCartDataMapper
{
    /**
     * @var SamplesCartInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param SamplesCartInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        SamplesCartInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|SamplesCartInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var SamplesCart $item */
        foreach ($collection->getItems() as $item) {
            /** @var SamplesCartInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
