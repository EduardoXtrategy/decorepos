<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Model\Elasticsearch\Adapter\IndexedDataMapper;

class MostViewed extends IndexedDataMapper
{
    const FIELD_NAME = 'most_viewed';

    public function getIndexerCode()
    {
        return 'sensei_sortingpro_most_viewed';
    }
}
