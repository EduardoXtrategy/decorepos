<?php

namespace Sensei\SortingPro\Model\Source;

use Magento\Catalog\Model\Config\Source\ListSort;

class SearchSort extends ListSort
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        array_shift($options);
        array_unshift($options, [
            'value' => 'relevance',
            'label' => __('Relevance')
        ]);

        return $options;
    }
}
