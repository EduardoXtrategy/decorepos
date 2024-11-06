<?php

namespace Uzer\OnDemand\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OnDemandRequest extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_ondemand_requests_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('uzer_ondemand_requests', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
