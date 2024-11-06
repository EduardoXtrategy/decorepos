<?php

namespace Sensei\SortingPro\Model\Source;

class ProductAttribute implements \Magento\Framework\Data\OptionSourceInterface
{
    private $options;

    private $eavConfig;

    public function __construct(
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->eavConfig = $eavConfig;
    }

    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [['value' => '', 'label' => ' ']];
            $attributes = $this->eavConfig->getEntityType(\Magento\Catalog\Model\Product::ENTITY)
                ->getAttributeCollection()
                ->addFieldToFilter('frontend_input', ['nin' => ['gallery', 'textarea']])
                ->addFieldToFilter('used_in_product_listing', 1)
                ->getItems();

            foreach ($attributes as $item) {
                $this->options[] = [
                    'value' => $item->getAttributeCode(),
                    'label' => __($item->getFrontendLabel()),
                ];
            }
        }

        return $this->options;
    }
}
