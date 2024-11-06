<?php

namespace Uzer\Sales\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OrderRestore extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sales_order_returns_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('sales_order_returns', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
