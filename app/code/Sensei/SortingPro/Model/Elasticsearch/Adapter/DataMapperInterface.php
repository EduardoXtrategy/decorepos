<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter;

interface DataMapperInterface
{

    public function map($entityId, array $entityIndexData, $storeId, $context = []);

    public function isAllowed($storeId);
}
