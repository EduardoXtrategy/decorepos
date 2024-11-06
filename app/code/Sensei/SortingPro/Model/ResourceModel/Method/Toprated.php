<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

use Magento\Framework\Exception\LocalizedException;

class Toprated extends AbstractMethod
{
    const MAIN_TABLE = 'review_entity_summary';

    protected $reviewResource;

    private $entityTypeId = null;

    public function __construct(
        Context $context,
        \Magento\Framework\Escaper $escaper,
        \Magento\Review\Model\ResourceModel\Review $reviewResource,
        $connectionName = null,
        $methodCode = '',
        $methodName = ''
    ) {
        parent::__construct($context, $escaper, $connectionName, $methodCode, $methodName);
        $this->reviewResource = $reviewResource;
        $this->indexConnection = $this->getConnection();
    }

    /**
     * Returns Sorting method Table Column name
     * which is using for order collection
     */
    public function getSortingColumnName()
    {
        return 'rating_summary_field';
    }

    public function getSortingFieldName()
    {
        return 'rating_summary';
    }

    public function getAlias()
    {
        return $this->getSortingColumnName();
    }

    public function apply($collection, $direction)
    {
        try {
            $collection->joinField(
                $this->getSortingColumnName(),          // alias
                $this->getIndexTableName(),         // table
                $this->getSortingFieldName(),   // field
                $this->getProductColumn() . '=entity_id',     // bind
                $this->getConditions(),          // conditions
                'left'                          // join type
            );
        } catch (LocalizedException $e) {
            // A joined field with this alias is already declared.
            $this->logger->warning(
                'Failed on join table for Sensei Commerce sorting method: ' . $e->getMessage(),
                ['method_code' => $this->getMethodCode()]
            );
        } catch (\Exception $e) {
            $this->logger->critical($e, ['method_code' => $this->getMethodCode()]);
        }

        return $this;
    }

    /**
     * Get Review entity type id for product
     */
    private function getEntityTypeId()
    {
        if ($this->entityTypeId === null) {
            $this->entityTypeId = $this->reviewResource->getEntityIdByCode(
                \Magento\Review\Model\Review::ENTITY_PRODUCT_CODE
            );
        }

        return $this->entityTypeId;
    }

    public function getIndexTableName()
    {
        if ($this->helper->isYotpoEnabled()) {
            $table = \Sensei\Yotpo\Model\ResourceModel\YotpoReview::MAIN_TABLE;
        } else {
            $table = self::MAIN_TABLE;
        }

        return $table;
    }

    private function getConditions()
    {
        $conditions = ['store_id' => $this->storeManager->getStore()->getId()];
        if (!$this->helper->isYotpoEnabled()) {
            $conditions['entity_type'] = $this->getEntityTypeId();
        }

        return $conditions;
    }

    private function getProductColumn()
    {
        $column = $this->helper->isYotpoEnabled() ?
            'product_id' :
            'entity_pk_value';

        return $column;
    }

    public function getIndexedValues($storeId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable($this->getIndexTableName()),
            ['product_id' => $this->getProductColumn(), 'value' => $this->getSortingFieldName()]
        );
        foreach ($this->getConditions() as $field => $value) {
            $select->where($field . ' = ?', $value);
        }

        return $this->getConnection()->fetchPairs($select);
    }
}
