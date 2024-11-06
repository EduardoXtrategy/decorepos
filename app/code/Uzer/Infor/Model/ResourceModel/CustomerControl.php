<?php

namespace Uzer\Infor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerControl extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_control_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('customer_control', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
