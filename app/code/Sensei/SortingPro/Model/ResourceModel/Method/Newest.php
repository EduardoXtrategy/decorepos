<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

class Newest extends AbstractMethod
{
    public function getSortingColumnName()
    {
        $attributeCode = $this->helper->getScopeValue('new/new_attr');
        if ($attributeCode) {
            return $attributeCode;
        }

        return 'created_at';
    }

    public function getAlias()
    {
        return $this->getSortingColumnName();
    }

    public function apply($collection, $direction)
    {
        return $this;
    }

    public function getIndexedValues($storeId)
    {
        return [];
    }
}
