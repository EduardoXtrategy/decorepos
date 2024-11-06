<?php

namespace Uzer\Infor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerAddressControl extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_address_control_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('customer_address_control', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
