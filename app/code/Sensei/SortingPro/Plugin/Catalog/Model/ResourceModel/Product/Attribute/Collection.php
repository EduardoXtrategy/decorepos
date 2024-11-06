<?php

namespace Sensei\SortingPro\Plugin\Catalog\Model\ResourceModel\Product\Attribute;

use Sensei\SortingPro\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection as AttributeCollection;
use Magento\Framework\DB\Select;

class Collection
{

    private $helper;

    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    public function afterAddToIndexFilter($subject, $result)
    {
        if ($this->helper->isElasticSort(true)) {
            $parts = $result->getSelect()->getPart(Select::WHERE);
            $conditions = array_pop($parts);
            $newCondition = $result->getConnection()->quoteInto(
                'main_table.attribute_code IN (?)',
                array_merge($this->helper->getSenseiAttributesCodes(), ['small_image'])
            );
            $conditions = str_replace(
                'additional_table.is_searchable',
                $newCondition . ' OR additional_table.is_searchable',
                $conditions
            );
            $parts[] = $conditions;
            $result->getSelect()->setPart(Select::WHERE, $parts);
        }

        return $result;
    }
}
