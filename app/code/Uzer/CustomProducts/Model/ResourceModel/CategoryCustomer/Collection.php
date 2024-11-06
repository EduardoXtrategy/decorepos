<?php

namespace Uzer\CustomProducts\Model\ResourceModel\CategoryCustomer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\CustomProducts\Model\CategoryCustomer;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomer as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'category_customers_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(CategoryCustomer::class, ResourceModel::class);
    }
}
