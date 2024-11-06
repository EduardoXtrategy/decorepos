<?php

namespace Uzer\Samples\Model\ResourceModel\SampleKit;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Samples\Model\ResourceModel\SampleKit as ResourceModel;
use Uzer\Samples\Model\SampleKit as Model;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sample_kits_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
