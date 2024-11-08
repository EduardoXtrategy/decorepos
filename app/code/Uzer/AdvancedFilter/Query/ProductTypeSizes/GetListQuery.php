<?php

namespace Uzer\AdvancedFilter\Query\ProductTypeSizes;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Uzer\AdvancedFilter\Mapper\ProductTypeSizesDataMapper;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes\Collection;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes\CollectionFactory;

/**
 * Get ProductTypeSizes list by search criteria query.
 */
class GetListQuery
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CollectionFactory
     */
    private $entityCollectionFactory;

    /**
     * @var ProductTypeSizesDataMapper
     */
    private $entityDataMapper;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $entityCollectionFactory
     * @param ProductTypeSizesDataMapper $entityDataMapper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        CollectionProcessorInterface      $collectionProcessor,
        CollectionFactory $entityCollectionFactory,
        ProductTypeSizesDataMapper        $entityDataMapper,
        SearchCriteriaBuilder             $searchCriteriaBuilder,
        SearchResultsInterfaceFactory     $searchResultFactory
    )
    {
        $this->collectionProcessor = $collectionProcessor;
        $this->entityCollectionFactory = $entityCollectionFactory;
        $this->entityDataMapper = $entityDataMapper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * Get ProductTypeSizes list by search criteria.
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     *
     * @return SearchResultsInterface
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->entityCollectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
