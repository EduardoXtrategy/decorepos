<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Model\Elasticsearch\Adapter\IndexedDataMapper;

class Toprated extends IndexedDataMapper
{
    const FIELD_NAME = 'rating_summary_field';

    public function getIndexerCode()
    {
        return 'sensei_yotpo_review';
    }
}
