<?php

namespace Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\AdvancedFilter\Model\ProductTypeSizes;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes as ResourcesModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'product_type_sizes_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ProductTypeSizes::class, ResourcesModel::class);
    }

    public function deleteAll()
    {
        $this->_resource->getConnection()->delete($this->getMainTable());
    }
}
