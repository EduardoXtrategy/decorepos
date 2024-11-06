<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Model\Elasticsearch\Adapter\IndexedDataMapper;

class Commented extends IndexedDataMapper
{
    const FIELD_NAME = 'reviews_count';

    public function getIndexerCode()
    {
        return 'sensei_yotpo_review';
    }
}
