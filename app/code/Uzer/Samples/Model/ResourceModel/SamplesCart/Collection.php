<?php

namespace Uzer\Samples\Model\ResourceModel\SamplesCart;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Samples\Model\ResourceModel\SamplesCart as ResourceModel;
use Uzer\Samples\Model\SamplesCart;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'samples_cart_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(SamplesCart::class, ResourceModel::class);
    }

    /**
     * Add store filter to collection
     * @param array|int|\Magento\Store\Model\Store $store
     * @param boolean $withAdmin
     * @return \Uzer\Samples\Model\ResourceModel\SamplesCart\Collection
     */
    public function addStoreFilter($store, $withAdmin = true): \Uzer\Samples\Model\ResourceModel\SamplesCart\Collection
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
