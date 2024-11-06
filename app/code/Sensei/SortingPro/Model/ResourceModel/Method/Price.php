<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

class Price extends AbstractMethod
{

    public function apply($collection, $direction)
    {
        return $this;
    }

    public function getAlias()
    {
        return 'price';
    }

    public function getIndexedValues($storeId)
    {
        return [];
    }
}
