<?php

namespace Uzer\Catalog\Model\ConfigurableProduct\ResourceModel\Product;

use Magento\Framework\DB\Select;

class StockStatusBaseSelectProcessor extends \Magento\ConfigurableProduct\Model\ResourceModel\Product\StockStatusBaseSelectProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process(Select $select): Select
    {
        return $select;
    }
}
