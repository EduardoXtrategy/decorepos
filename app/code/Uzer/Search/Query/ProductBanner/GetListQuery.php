<?php

namespace Uzer\Search\Query\ProductBanner;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Uzer\Search\Api\Data\ProductBannerSearchResultsInterface;
use Uzer\Search\Api\Data\ProductBannerSearchResultsInterfaceFactory;
use Uzer\Search\Api\GetProductBannerListInterface;
use Uzer\Search\Mapper\ProductBannerDataMapper;
use Uzer\Search\Model\ResourceModel\ProductBannerModel\ProductBannerCollection;
use Uzer\Search\Model\ResourceModel\ProductBannerModel\ProductBannerCollectionFactory;

/**
 * Get ProductBanner list by search criteria query.
 */
class GetListQuery implements GetProductBannerListInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ProductBannerCollectionFactory
     */
    private $entityCollectionFactory;

    /**
     * @var ProductBannerDataMapper
     */
    private $entityDataMapper;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ProductBannerSearchResultsInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ProductBannerCollectionFactory $entityCollectionFactory
     * @param ProductBannerDataMapper $entityDataMapper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductBannerSearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        CollectionProcessorInterface               $collectionProcessor,
        ProductBannerCollectionFactory             $entityCollectionFactory,
        ProductBannerDataMapper                    $entityDataMapper,
        SearchCriteriaBuilder                      $searchCriteriaBuilder,
        ProductBannerSearchResultsInterfaceFactory $searchResultFactory
    )
    {
        $this->collectionProcessor = $collectionProcessor;
        $this->entityCollectionFactory = $entityCollectionFactory;
        $this->entityDataMapper = $entityDataMapper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): ProductBannerSearchResultsInterface
    {
        /** @var ProductBannerCollection $collection */
        $collection = $this->entityCollectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var ProductBannerSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
