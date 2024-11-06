<?php

namespace Uzer\Samples\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SamplesCart extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'samples_cart_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('samples_cart', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
