<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Model\Elasticsearch;

use Sensei\SortingPro\Model\ResourceModel\LoadSkuMap;

class SkuRegistry
{

    private $skuRelations;

    private $loadSkuMap;

    public function __construct(LoadSkuMap $loadSkuMap)
    {
        $this->loadSkuMap = $loadSkuMap;
    }

    public function save(array $entityIds)
    {
        $this->skuRelations = $this->loadSkuMap->execute($entityIds);
    }

    public function clear()
    {
        $this->skuRelations = null;
    }

    public function getSku(int $entityId): string
    {
        return $this->skuRelations[$entityId] ?? '';
    }
}
