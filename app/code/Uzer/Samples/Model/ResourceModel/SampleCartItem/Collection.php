<?php

namespace Uzer\Samples\Model\ResourceModel\SampleCartItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Samples\Model\ResourceModel\SampleCartItem as ResourceModel;
use Uzer\Samples\Model\SampleCartItem as Model;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sample_cart_items_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
