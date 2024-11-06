<?php

namespace Uzer\MostviewedProducts\Model;

use Magento\Framework\Data\OptionSourceInterface;

class WidgetType implements OptionSourceInterface
{

    public function toOptionArray(): array
    {
        return array(
            ['value' => 'featured', 'label' => __('Featured')],
            ['value' => 'most_viewed', 'label' => __('Most Viewed')]
        );
    }
}
