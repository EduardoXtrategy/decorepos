<?php

namespace Uzer\Jobs\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class RequestJob extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_request_jobs_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('uzer_request_jobs', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
