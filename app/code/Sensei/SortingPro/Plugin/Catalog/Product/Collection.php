<?php

namespace Sensei\SortingPro\Plugin\Catalog\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Framework\DB\Select;
use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;

/**
 * Plugin Collection
 * plugin name: Sensei_SortingPro::SortingMethodsProcessor
 * type: \Magento\Catalog\Model\ResourceModel\Product\Collection
 */
class Collection
{
    const PROCESS_FLAG = 'scsort_process';

    private $helper;

    private $methodProvider;

    private $adapterFactory;

    private $imageMethod;

    private $stockMethod;

    private $skipAttributes = [];

    public function __construct(
        \Sensei\SortingPro\Helper\Data $helper,
        \Sensei\SortingPro\Model\MethodProvider $methodProvider,
        \Sensei\SortingPro\Model\ResourceModel\Method\Image $imageMethod,
        \Sensei\SortingPro\Model\ResourceModel\Method\Instock $stockMethod,
        \Sensei\SortingPro\Model\SortingAdapterFactory $adapterFactory
    ) {
        $this->helper         = $helper;
        $this->methodProvider = $methodProvider;
        $this->adapterFactory = $adapterFactory;
        $this->imageMethod    = $imageMethod;
        $this->stockMethod    = $stockMethod;
    }

    public function beforeSetOrder($subject, $attribute, $dir = Select::SQL_DESC)
    {
        $subject->setFlag(self::PROCESS_FLAG, true);
        $this->applyHighPriorityOrders($subject, $dir);
        $flagName = $this->getFlagName($attribute);
        if ($subject->getFlag($flagName)) {
            if ($this->helper->isElasticSort()) {
                $this->skipAttributes[] = $flagName;
            } else {
                // attribute already used in sorting; disable double sorting by renaming attribute into not existing
                $attribute .= '_ignore';
            }
        } else {
            $method = $this->methodProvider->getMethodByCode($attribute);
            if ($method) {
                $method->apply($subject, $dir);
                $attribute = $method->getAlias();
            }
            if (!$this->helper->isElasticSort()) {
                if ($attribute == 'relevance' && !$subject->getFlag($this->getFlagName('sc_relevance'))) {
                    $this->addRelevanceSorting($subject, $dir);
                    $attribute = 'sc_relevance';
                }
                if ($attribute == 'price') {
                    $subject->addOrder($attribute, $dir);
                    $attribute .= '_ignore';
                }
            }
            $subject->setFlag($flagName, true);
        }

        $subject->setFlag(self::PROCESS_FLAG, false);

        return [$attribute, $dir];
    }

    public function aroundSetOrder($subject, callable $proceed, $attribute, $dir = Select::SQL_DESC)
    {
        $flagName = $this->getFlagName($attribute);
        if (!in_array($flagName, $this->skipAttributes)) {
            $proceed($attribute, $dir);
        }

        return $subject;
    }

    private function getFlagName($attribute)
    {
        if ($attribute == 'price_asc' || $attribute == 'price_desc') {
            $attribute = 'price';
        }
        if (is_string($attribute)) {
            return 'sorted_by_' . $attribute;
        }

        return 'sensei_sorting';
    }

    private function applyHighPriorityOrders($collection, $dir)
    {
        if (!$collection->getFlag($this->getFlagName('high'))) {
            $collection->setFlag($this->getFlagName('high'), true);
            if ($this->helper->isElasticSort()) {
                $collection->setOrder('non_images_last', Select::SQL_DESC);
                $collection->setOrder('out_of_stock_last', Select::SQL_DESC);
            } else {
                $this->stockMethod->apply($collection, $dir);
                $this->imageMethod->apply($collection, $dir);
            }
        }
    }

    private function addRelevanceSorting($collection)
    {
        $collection->getSelect()->columns(['sc_relevance' => new \Zend_Db_Expr(
            'search_result.'. TemporaryStorage::FIELD_SCORE
        )]);
        $collection->addExpressionAttributeToSelect('sc_relevance', 'sc_relevance', []);

        // remove last item from columns because e.am_relevance from addExpressionAttributeToSelect not exist
        $columns = $collection->getSelect()->getPart(\Zend_Db_Select::COLUMNS);
        array_pop($columns);
        $collection->getSelect()->setPart(\Zend_Db_Select::COLUMNS, $columns);
        $collection->setFlag($this->getFlagName('sc_relevance'), true);
    }

    public function beforeAddOrder($subject, $attribute, $dir = Select::SQL_DESC)
    {
        if (!$subject->getFlag(self::PROCESS_FLAG)) {
            $result =  $this->beforeSetOrder($subject, $attribute, $dir);
        }

        return $result ?? [$attribute, $dir];
    }

    public function aroundAddOrder($subject, callable $proceed, $attribute, $dir = Select::SQL_DESC)
    {
        return $this->aroundSetOrder($subject, $proceed, $attribute, $dir);
    }
}
