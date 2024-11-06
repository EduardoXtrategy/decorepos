<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

class Commented extends Toprated
{
    public function getSortingColumnName()
    {
        $columnName = $this->helper->isYotpoEnabled() ? 'total_reviews' : 'reviews_count';

        return $columnName;
    }

    public function getSortingFieldName()
    {
        return $this->getSortingColumnName();
    }
}
