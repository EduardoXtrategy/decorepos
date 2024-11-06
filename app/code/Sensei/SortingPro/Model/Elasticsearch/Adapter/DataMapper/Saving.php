<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Helper\Data;
use Sensei\SortingPro\Model\Elasticsearch\Adapter\IndexedDataMapper;
use Sensei\SortingPro\Model\ResourceModel\Method\Saving as SavingResource;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Indexer\IndexerRegistry;

class Saving extends IndexedDataMapper
{
    const FIELD_NAME = 'saving';

    private $collectionFactory;

    public function __construct(
        IndexerRegistry $indexerRegistry,
        CollectionFactory $collectionFactory,
        SavingResource $resourceMethod,
        Data $helper
    ) {
        parent::__construct($indexerRegistry, $resourceMethod, $helper);
        $this->collectionFactory = $collectionFactory;
    }

    public function getIndexerCode()
    {
        return false;
    }

    protected function forceLoad($storeId)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addPriceData();
        $this->resourceMethod->setLimitColumns(true);
        $this->resourceMethod->apply($collection, '');
        return $this->resourceMethod->getConnection()->fetchPairs($collection->getSelect());
    }
}
