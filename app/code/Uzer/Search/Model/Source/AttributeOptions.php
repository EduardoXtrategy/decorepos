<?php

namespace Uzer\Search\Model\Source;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\OptionSourceInterface;

class AttributeOptions implements OptionSourceInterface
{

    public function toOptionArray(): array
    {
        $eavConfig = ObjectManager::getInstance()->create(\Magento\Eav\Model\Config::class);
        $attribute = $eavConfig->getAttribute('catalog_product', 'product_type_decowraps');
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
