<?php

namespace Uzer\Middleware\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OrderMiddleware extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sale_orders_middleware_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('sale_orders_middleware', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
