<?php

namespace Uzer\Jobs\Model\ResourceModel\RequestJob;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Jobs\Model\RequestJob;
use Uzer\Jobs\Model\ResourceModel\RequestJob as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_request_jobs_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(RequestJob::class, ResourceModel::class);
    }

    /**
     * Add store filter to collection
     * @param array|int|\Magento\Store\Model\Store $store
     * @param boolean $withAdmin
     * @return \Uzer\Jobs\Model\ResourceModel\RequestJob\Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof \Magento\Store\Model\Store) {
                $store = [$store->getId()];
            }
            if (!is_array($store)) {
                $store = [$store];
            }
            if ($withAdmin) {
                $store[] = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
            }
            $this->addFieldToFilter('store_id', ['in' => $store]);
        }
        return $this;
    }
}
