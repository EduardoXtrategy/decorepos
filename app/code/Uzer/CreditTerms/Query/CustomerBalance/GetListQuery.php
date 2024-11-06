<?php

namespace Uzer\CreditTerms\Query\CustomerBalance;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Uzer\CreditTerms\Api\Data\CustomerBalanceSearchResultsInterface;
use Uzer\CreditTerms\Api\Data\CustomerBalanceSearchResultsInterfaceFactory;
use Uzer\CreditTerms\Api\GetCustomerBalanceListInterface;
use Uzer\CreditTerms\Mapper\CustomerBalanceDataMapper;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance\CollectionFactory;

/**
 * Get CustomerBalance list by search criteria query.
 */
class GetListQuery implements GetCustomerBalanceListInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $entity;

    /**
     * @var CustomerBalanceDataMapper
     */
    private CustomerBalanceDataMapper $entityDataMapper;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var CustomerBalanceSearchResultsInterfaceFactory
     */
    private CustomerBalanceSearchResultsInterfaceFactory $searchResultFactory;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $entity
     * @param CustomerBalanceDataMapper $entityDataMapper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CustomerBalanceSearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        CollectionProcessorInterface                 $collectionProcessor,
        CollectionFactory                            $entity,
        CustomerBalanceDataMapper                    $entityDataMapper,
        SearchCriteriaBuilder                        $searchCriteriaBuilder,
        CustomerBalanceSearchResultsInterfaceFactory $searchResultFactory
    )
    {
        $this->collectionProcessor = $collectionProcessor;
        $this->entity = $entity;
        $this->entityDataMapper = $entityDataMapper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): CustomerBalanceSearchResultsInterface
    {
        $collection = $this->entity->create();
        if (is_null($searchCriteria)) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }
        $entityDataObjects = $this->entityDataMapper->map($collection);
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult->setSearchCriteria($searchCriteria);
    }
}
