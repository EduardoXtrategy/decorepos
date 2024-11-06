<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

/**
 * Class Wished
 * Sorting adapter for wished method
 */
class Wished extends AbstractIndexMethod
{

    public function getIndexTableName()
    {
        return 'sensei_sortingpro_wished';
    }

    public function getSortingColumnName()
    {
        return 'wished';
    }

    public function doReindex()
    {
        $select = $this->indexConnection->select();

        $select->group(['source_table.store_id', 'source_table.product_id']);

        $viewsNumExpr = new \Zend_Db_Expr('COUNT(source_table.wishlist_item_id)');

        $columns = [
            'product_id' => 'source_table.product_id',
            'store_id' => 'source_table.store_id',
            'wished' => $viewsNumExpr,
        ];

        $select->from(
            ['source_table' => $this->getTable('wishlist_item')],
            $columns
        );

        $this->addFromDate($select);

        $select->useStraightJoin();

        $wishedInfo = $this->indexConnection->fetchAll($select);

        if ($wishedInfo) {
            $this->getConnection()->insertArray($this->getMainTable(), array_keys($columns), $wishedInfo);
        }
    }


    private function addFromDate($select)
    {
        $period = (int)$this->helper->getScopeValue('wished/wished_period');
        if ($period) {
            $from = $this->date->date(
                \Magento\Framework\DB\Adapter\Pdo\Mysql::TIMESTAMP_FORMAT,
                $this->date->timestamp() - $period * 24 * 3600
            );
            $select->where('source_table.added_at >= ?', $from);
            return true;
        }

        return false;
    }

    public function getIndexedValues($storeId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getMainTable(),
            ['product_id', 'value' => 'wished']
        )->where('store_id = ?', $storeId);

        return $this->getConnection()->fetchPairs($select);
    }
}
