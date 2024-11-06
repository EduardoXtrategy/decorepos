<?php

namespace Sensei\SortingPro\Model\Source;

use Magento\Catalog\Model\Config;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Registry;

class ListDisabled implements OptionSourceInterface
{

    private $catalogConfig;

    private $registry;

    public function __construct(Config $catalogConfig, Registry $registry)
    {
        $this->catalogConfig = $catalogConfig;
        $this->registry = $registry;
    }

    public function toOptionArray()
    {
        $options = [];
        $this->registry->unregister('sorting_all_attributes');
        $this->registry->register('sorting_all_attributes', true);
        $allAttributes = $this->catalogConfig->getAttributeUsedForSortByArray();
        $this->registry->unregister('sorting_all_attributes');
        foreach ($allAttributes as $code => $label) {
            $options[] = [
                'label' => $label,
                'value' => $code
            ];
        }

        return $options;
    }
}
