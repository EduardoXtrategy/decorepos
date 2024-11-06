<?php

namespace Uzer\AdvancedFilter\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes as ResourceModel;

class ProductTypeSizes extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'product_type_sizes_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
