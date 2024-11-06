<?php

namespace Uzer\OnDemand\Model;

use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Framework\App\ObjectManager;

class AttributeData implements \Magento\Framework\Data\OptionSourceInterface
{


    public function toOptionArray(): array
    {
        $eavConfig = ObjectManager::getInstance()->create(\Magento\Eav\Model\Config::class);
        $attribute = $eavConfig->getAttribute('catalog_product', 'b_model');
        $items = [];
        $items[] = ['value' => 0, 'label' => __('Default banner')];
        if ($attribute) {
            foreach ($attribute->getOptions() as $option) {
                $items[] = ['value' => $option->getValue(), 'label' => $option->getLabel()];
            }
        }
        return $items;
    }
}
