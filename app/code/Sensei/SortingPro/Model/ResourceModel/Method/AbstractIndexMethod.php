<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

use Sensei\SortingPro\Api\IndexedMethodInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\CouldNotDeleteException;

abstract class AbstractIndexMethod extends AbstractMethod implements IndexedMethodInterface
{
    protected function _construct()
    {
        // product_id can be not unique
        $this->_init($this->getIndexTableName(), 'product_id');
    }

    abstract public function doReindex();

    public function beforeReindex()
    {
        try {
            if ($this->getMethodCode() != 'rating_summary') {
                $this->getConnection()->truncateTable($this->getMainTable());
            }
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Error while clear index sensei commerce sorting method: '), $e);
        }

        return $this;
    }


    public function reindex()
    {
        if ($this->getConnection()->getTransactionLevel() == 0) {
            $this->beforeReindex();

            try {
                if ($this->indexConnection) {
                    $this->doReindex();
                }
            } catch (\Exception $e) {
                $this->logger->critical(
                    $e,
                    ['method_code' => $this->getMethodCode()]
                );
                throw $e;
            }

            $this->afterReindex();
        }
    }


    public function afterReindex()
    {
        return $this;
    }


    public function getMainTable()
    {
        return $this->getTable($this->getIndexTableName());
    }


    public function getIndexTableName()
    {
        return 'sensei_sorting_' . $this->getMethodCode();
    }


    public function getSortingColumnName()
    {
        return $this->getMethodCode();
    }


    public function getAlias()
    {
        return $this->getMethodCode();
    }


    public function apply($collection, $direction)
    {
        try {
            $collection->joinField(
                $this->getAlias(),        // alias
                $this->getIndexTableName(),    // table
                $this->getSortingColumnName(), // field
                'product_id = entity_id',      // bind
                ['store_id' => $this->storeManager->getStore()->getId()], // conditions
                'left'                         // join type
            );
        } catch (LocalizedException $e) {
            // A joined field with this alias is already declared.
            $this->logger->warning(
                'Failed on join table for sensei commerce sorting method: ' . $e->getMessage(),
                ['method_code' => $this->getMethodCode()]
            );
        } catch (\Exception $e) {
            $this->logger->critical($e, ['method_code' => $this->getMethodCode()]);
        }

        return $this;
    }
}
