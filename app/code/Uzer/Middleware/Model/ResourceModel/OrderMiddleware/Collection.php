<?php

namespace Uzer\Middleware\Model\ResourceModel\OrderMiddleware;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Middleware\Model\OrderMiddleware as Model;
use Uzer\Middleware\Model\ResourceModel\OrderMiddleware as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sale_orders_middleware_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }


    public function addOrderIdFilter(int $oderId): Collection
    {
        return $this->addFieldToFilter('order_id', array('eq' => $oderId));
    }

    public function getCount(int $orderId): int
    {
        return $this->addOrderIdFilter($orderId)->count();
    }
}
