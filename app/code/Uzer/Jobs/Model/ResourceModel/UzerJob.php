<?php

namespace Uzer\Jobs\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class UzerJob extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_jobs_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('uzer_jobs', 'entity_id');
        $this->_useIsObjectNew = true;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return UzerJob
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object): UzerJob
    {
        $condition = ['jobs_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('uzer_jobs_store'), $condition);
        return parent::_beforeDelete($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return UzerJob
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object): UzerJob
    {
        return parent::_beforeSave($object);
    }


    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldIds = $this->lookupStoreIds($object->getId());
        $newIds = (array)$object->getStores();
        if (empty($newIds)) {
            $newIds = (array)$object->getStoreId();
        }
        $this->_updateLinks($object, $newIds, $oldIds, 'uzer_jobs_store', 'store_id');
        return parent::_afterSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Update post connections
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param array $newRelatedIds
     * @param array $oldRelatedIds
     * @param String $tableName
     * @param String $field
     * @return void
     */
    protected function _updateLinks(
        \Magento\Framework\Model\AbstractModel $object,
        array                                  $newRelatedIds,
        array                                  $oldRelatedIds,
                                               $tableName,
                                               $field
    )
    {
        $table = $this->getTable($tableName);
        $insert = array_diff($newRelatedIds, $oldRelatedIds);
        $delete = array_diff($oldRelatedIds, $newRelatedIds);
        if ($delete) {
            $where = ['jobs_id = ?' => (int)$object->getId(), $field . ' IN (?)' => $delete];
            $this->getConnection()->delete($table, $where);
        }
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = ['jobs_id' => (int)$object->getId(), $field => (int)$storeId];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $catalogId
     * @return array
     */
    public function lookupStoreIds($catalogId): array
    {
        return $this->_lookupIds($catalogId, 'uzer_jobs_store', 'store_id');
    }

    /**
     * Get ids to which specified item is assigned
     * @param int $catalogId
     * @param string $tableName
     * @param string $field
     * @return array
     */
    protected function _lookupIds(int $catalogId, string $tableName, string $field): array
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where(
            'jobs_id = ?',
            (int)$catalogId
        );
        return $adapter->fetchCol($select);
    }
}
