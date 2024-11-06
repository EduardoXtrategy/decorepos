<?php

namespace Uzer\Infor\Model\ResourceModel\CustomerControl;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Infor\Model\CustomerControl as Model;
use Uzer\Infor\Model\ResourceModel\CustomerControl as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_control_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
