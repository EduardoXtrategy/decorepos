<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

class MostViewed extends AbstractIndexMethod
{

    public function getIndexTableName()
    {
        return 'sensei_sortingpro_most_viewed';
    }

    public function getSortingColumnName()
    {
        return 'views_num';
    }

    public function doReindex()
    {
        $select = $this->indexConnection->select();

        $select->group(['source_table.store_id', 'source_table.object_id']);

        $viewsNumExpr = new \Zend_Db_Expr('COUNT(source_table.event_id)');

        $columns = [
            'product_id' => 'source_table.object_id',
            'store_id' => 'source_table.store_id',
            'views_num' => $viewsNumExpr,
        ];

        $select->from(
            ['source_table' => $this->getTable('report_event')],
            $columns
        )->where(
            'source_table.event_type_id = ?',
            \Magento\Reports\Model\Event::EVENT_PRODUCT_VIEW
        );

        $this->addFromDate($select);

        $havingPart = $this->indexConnection->prepareSqlCondition($viewsNumExpr, ['gt' => 0]);
        $select->having($havingPart);

        $select->useStraightJoin();

        $viewedInfo = $this->indexConnection->fetchAll($select);

        if ($viewedInfo) {
            $this->getConnection()->insertArray($this->getMainTable(), array_keys($columns), $viewedInfo);
        }
    }

    private function addFromDate($select)
    {
        $period = (int)$this->helper->getScopeValue('most_viewed/viewed_period');
        if ($period) {
            $from = $this->date->date(
                \Magento\Framework\DB\Adapter\Pdo\Mysql::TIMESTAMP_FORMAT,
                $this->date->timestamp() - $period * 24 * 3600
            );
            $select->where('source_table.logged_at >= ?', $from);
            return true;
        }

        return false;
    }

    public function apply($collection, $direction)
    {
        $attributeCode = $this->helper->getScopeValue('most_viewed/viewed_attr');
        if ($attributeCode) {
            if ($this->helper->isElasticSort()) {
                $collection->addAttributeToSort($attributeCode, $direction);
            } else {
                $collection->addAttributeToSelect($attributeCode);
                $collection->addOrder($attributeCode, $direction);
            }
        }
        return parent::apply($collection, $direction);
    }

    public function getIndexedValues($storeId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getMainTable(),
            ['product_id', 'value' => 'views_num']
        )->where('store_id = ?', $storeId);

        return $this->getConnection()->fetchPairs($select);
    }
}
