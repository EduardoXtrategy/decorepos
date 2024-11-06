<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Model\Elasticsearch\Adapter\IndexedDataMapper;

class Wished extends IndexedDataMapper
{
    const FIELD_NAME = 'wished';

    public function getIndexerCode()
    {
        return 'sensei_sortingpro_wished';
    }
}
