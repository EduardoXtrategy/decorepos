<?php

namespace Uzer\Samples\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SampleKit extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sample_kits_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('sample_kits', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
