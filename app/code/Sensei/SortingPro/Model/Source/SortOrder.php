<?php

namespace Sensei\SortingPro\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class SortOrder implements ArrayInterface
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::SORT_DESC,
                'label' => __('DESC')
            ],
            [
                'value' => self::SORT_ASC,
                'label' => __('ASC')
            ]
        ];

        return $options;
    }
}