<?php

namespace Uzer\CustomProducts\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerProduct extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_products_resource';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('customer_products', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
