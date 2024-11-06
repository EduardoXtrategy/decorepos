<?php

namespace Uzer\Infor\Model\ResourceModel\CustomerAddressControl;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Infor\Model\CustomerAddressControl as Model;
use Uzer\Infor\Model\ResourceModel\CustomerAddressControl as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_address_control_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
