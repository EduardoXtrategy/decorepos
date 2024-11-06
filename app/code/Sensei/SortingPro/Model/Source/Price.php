<?php

namespace Sensei\SortingPro\Model\Source;

class Price implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            [
                'value' => 'min_price',
                'label' => __('Minimal Price'),
            ],
            [
                'value' => 'price',
                'label' => __('Price'),
            ],
            [
                'value' => 'final_price',
                'label' => __('Final Price'),
            ],
        ];
    }
}
