<?php

namespace Uzer\Checkoutstep\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PurchaseOrder extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_sales_purchase_order_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('uzer_sales_purchase_order', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
