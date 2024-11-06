<?php

namespace Sensei\SortingPro\Model\Source;

class SearchSortAfter extends SearchSort
{
    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        array_unshift($options, [
            'value' => '',
            'label' => __('--Please Select--')
        ]);

        return $options;
    }
}
