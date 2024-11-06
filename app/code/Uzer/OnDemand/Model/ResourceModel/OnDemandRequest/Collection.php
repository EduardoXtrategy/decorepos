<?php

namespace Uzer\OnDemand\Model\ResourceModel\OnDemandRequest;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\OnDemand\Model\OnDemandRequest as Model;
use Uzer\OnDemand\Model\ResourceModel\OnDemandRequest as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_ondemand_requests_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
