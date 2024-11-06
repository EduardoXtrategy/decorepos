<?php

namespace Uzer\AdvancedFilter\Mapper;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\AdvancedFilter\Api\Data\ProductTypeSizesInterface;
use Uzer\AdvancedFilter\Api\Data\ProductTypeSizesInterfaceFactory;
use Uzer\AdvancedFilter\Model\ProductTypeSizes;

/**
 * Converts a collection of ProductTypeSizes entities to an array of data transfer objects.
 */
class ProductTypeSizesDataMapper
{
    /**
     * @var ProductTypeSizesInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param ProductTypeSizesInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        ProductTypeSizesInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|ProductTypeSizesInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var ProductTypeSizes $item */
        foreach ($collection->getItems() as $item) {
            /** @var ProductTypeSizesInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
