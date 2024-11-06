<?php

namespace Uzer\Samples\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SampleOrder extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sample_orders_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('sample_orders', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
