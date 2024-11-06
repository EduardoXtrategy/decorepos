<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter;

use Magento\Framework\Indexer\IndexerRegistry;
use Sensei\SortingPro\Model\ResourceModel\Method\AbstractMethod;
use Sensei\SortingPro\Helper\Data;


abstract class IndexedDataMapper implements DataMapperInterface
{
    const DEFAULT_VALUE = 0;

    protected $resourceMethod;

    protected $values = [];

    private $helper;

    private $indexerRegistry;

    public function __construct(
        IndexerRegistry $indexerRegistry,
        AbstractMethod $resourceMethod,
        Data $helper
    ) {
        $this->resourceMethod = $resourceMethod;
        $this->helper = $helper;
        $this->indexerRegistry = $indexerRegistry;
    }

    abstract public function getIndexerCode();

    protected function loadValuesArray($storeId)
    {
        if (!isset($this->values[$storeId])) {
            $this->values[$storeId] = $this->forceLoad($storeId);
        }
    }

    protected function forceLoad($storeId)
    {
        try {
            $indexer = $this->indexerRegistry->get($this->getIndexerCode());
            $indexer->reindexAll();
        } catch (\InvalidArgumentException $e) {
            ;//No action required
        }

        return $this->resourceMethod->getIndexedValues($storeId);
    }


    public function isAllowed($storeId)
    {
        return !$this->helper->isMethodDisabled($this->resourceMethod->getMethodCode(), $storeId);
    }


    public function map($entityId, array $entityIndexData, $storeId, $context = [])
    {
        $this->loadValuesArray($storeId);
        $value = isset($this->values[$storeId][$entityId]) ? $this->values[$storeId][$entityId] : self::DEFAULT_VALUE;

        return [static::FIELD_NAME => $value];
    }
}
