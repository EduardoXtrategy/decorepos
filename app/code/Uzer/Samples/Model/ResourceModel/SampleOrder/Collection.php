<?php

namespace Uzer\Samples\Model\ResourceModel\SampleOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Samples\Model\ResourceModel\SampleOrder as ResourceModel;
use Uzer\Samples\Model\SampleOrder;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sample_orders_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(SampleOrder::class, ResourceModel::class);
    }

    /**
     * Add store filter to collection
     * @param array|int|\Magento\Store\Model\Store $store
     * @param boolean $withAdmin
     * @return \Uzer\Samples\Model\ResourceModel\SampleOrder\Collection
     */
    public function addStoreFilter($store, $withAdmin = true): \Uzer\Samples\Model\ResourceModel\SampleOrder\Collection
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof \Magento\Store\Model\Store){
                $this->addFieldToFilter('store_id', ['in' => $store->getId()]);
            }else{
                $this->addFieldToFilter('store_id', ['in' => $store]);
            }
        }
        return $this;
    }
}
