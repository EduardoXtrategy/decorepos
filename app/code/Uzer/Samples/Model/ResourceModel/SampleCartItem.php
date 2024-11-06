<?php

namespace Uzer\Samples\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SampleCartItem extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sample_cart_items_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('samples_cart_item', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
