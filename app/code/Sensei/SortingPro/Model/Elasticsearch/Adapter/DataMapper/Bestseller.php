<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Model\Elasticsearch\Adapter\IndexedDataMapper;

class Bestseller extends IndexedDataMapper
{
    const FIELD_NAME = 'bestsellers';

    public function getIndexerCode()
    {
        return 'sensei_sortingpro_bestseller';
    }
}
