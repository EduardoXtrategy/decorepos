<?php

namespace Uzer\Jobs\Model\ResourceModel\UzerJob;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Jobs\Model\ResourceModel\UzerJob as ResourceModel;
use Uzer\Jobs\Model\UzerJob;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_jobs_collection';
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(UzerJob::class, ResourceModel::class);
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }


    /**
     * Add field filter to collection
     *
     * @param string|array $field
     * @param null|string|array $condition
     * @return \Uzer\Catalogs\Model\ResourceModel\Catalog\Collection
     */
    public function addFieldToFilter($field, $condition = null): Collection
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add store filter to collection
     * @param array|int|\Magento\Store\Model\Store $store
     * @param boolean $withAdmin
     * @return \Uzer\jobs\Model\ResourceModel\UzerJob\Collection
     */
    public function addStoreFilter($store, $withAdmin = true): Collection
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
            $this->addFilter('store', array('in' => $store), 'public');
        }
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        foreach (array('store') as $key) {
            if ($this->getFilter($key)) {
                $this->getSelect()->join(
                    [$key . '_table' => $this->getTable('uzer_jobs_' . $key)],
                    'main_table.entity_id = ' . $key . '_table.jobs_id',
                    []
                )->group(
                    'main_table.entity_id'
                );
            }
        }
        parent::_renderFiltersBefore();
    }


}
