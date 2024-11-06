<?php

namespace Sensei\SortingPro\Plugin\Elasticsearch\Model\Adapter\FieldMapper\Product;

use Sensei\SortingPro\Helper\Data;
use Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\AttributeAdapter as NativeAttributeAdapter;

class AttributeAdapter
{

    private $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function afterIsSortable($subject, $result)
    {
        if ($this->helper->isElasticSort(true)
            && in_array(
                $subject->getAttributeCode(),
                $this->helper->getSenseiAttributesCodes()
            )
        ) {
            $result = true;
        }

        return $result;
    }
}
