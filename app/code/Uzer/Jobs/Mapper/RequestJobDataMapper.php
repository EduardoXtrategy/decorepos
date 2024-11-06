<?php

namespace Uzer\Jobs\Mapper;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Jobs\Api\Data\RequestJobInterface;
use Uzer\Jobs\Api\Data\RequestJobInterfaceFactory;
use Uzer\Jobs\Model\RequestJob;

/**
 * Converts a collection of RequestJob entities to an array of data transfer objects.
 */
class RequestJobDataMapper
{
    /**
     * @var RequestJobInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param RequestJobInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        RequestJobInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|RequestJobInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var RequestJob $item */
        foreach ($collection->getItems() as $item) {
            /** @var RequestJobInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
