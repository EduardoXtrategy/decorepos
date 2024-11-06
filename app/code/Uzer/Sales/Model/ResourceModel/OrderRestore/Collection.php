<?php

namespace Uzer\Sales\Model\ResourceModel\OrderRestore;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Sales\Model\OrderRestore as Model;
use Uzer\Sales\Model\ResourceModel\OrderRestore as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sales_order_returns_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
