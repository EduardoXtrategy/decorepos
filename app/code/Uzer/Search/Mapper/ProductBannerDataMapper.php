<?php

namespace Uzer\Search\Mapper;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Search\Api\Data\ProductBannerInterface;
use Uzer\Search\Api\Data\ProductBannerInterfaceFactory;
use Uzer\Search\Model\ProductBannerModel;

/**
 * Converts a collection of ProductBanner entities to an array of data transfer objects.
 */
class ProductBannerDataMapper
{
    /**
     * @var ProductBannerInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param ProductBannerInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        ProductBannerInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|ProductBannerInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var ProductBannerModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var ProductBannerInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();

            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }
        return $results;
    }
}
