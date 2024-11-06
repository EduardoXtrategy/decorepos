<?php

namespace Sensei\SortingPro\Helper;

use Amasty\Base\Model\Serializer;
use Magento\CatalogInventory\Model\Configuration;
use Magento\CatalogSearch\Model\ResourceModel\EngineInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIG_SORT_ORDER = 'general/sort_order';

    const SEARCH_SORTING = 'scsorting_search';

    /**
     * @var Serializer
     */
    private $serializer;

    private $registry;

    private $magentoVersion;

    private $storeManager;

    public function __construct(
        \Amasty\Base\Model\Serializer $serializer,
        Registry $registry,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Amasty\Base\Model\MagentoVersion $magentoVersion
    ) {
        parent::__construct($context);
        $this->serializer = $serializer;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->magentoVersion = $magentoVersion;
    }

    public function getScopeValue($path, $store = null)
    {
        return $this->scopeConfig->getValue(
            'scsorting/' . $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function isMethodDisabled($methodCode, $storeId = null)
    {
        $result = false;
        if (!$this->registry->registry('sorting_all_attributes')) {
            $disabledMethods = $this->getScopeValue('general/disable_methods', $storeId);
            if ($disabledMethods && !empty($disabledMethods)) {
                $disabledMethods = explode(',', $disabledMethods);
                foreach ($disabledMethods as $disabledCode) {
                    if (trim($disabledCode) == $methodCode) {
                        $result = true;
                        break;
                    }
                }
            }
        }

        return $result;
    }

    public function getSearchSorting()
    {
        $defaultSorting = [];
        foreach (['search_1', 'search_2', 'search_3'] as $path) {
            if ($sort = $this->getScopeValue('default_sorting/' . $path)) {
                $defaultSorting[] = $sort;
            }
        }

        return $defaultSorting;
    }

    public function isYotpoEnabled()
    {
        return $this->getScopeValue('rating_summary/yotpo')
            && $this->_moduleManager->isEnabled('Yotpo_Yotpo');
    }


    public function getQtyOutStock($storeId = null)
    {
        return (int)$this->scopeConfig->getValue(
            Configuration::XML_PATH_MIN_QTY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getSortOrder()
    {
        $value = $this->getScopeValue(self::CONFIG_SORT_ORDER);
        if ($value) {
            $value = $this->serializer->unserialize($value);
        }
        if (!$value) {
            $value = [];
        }

        return $value;
    }

    public function getCategorySorting($store = null)
    {
        $defaultSorting = [];
        foreach (['category_1', 'category_2', 'category_3'] as $path) {
            if ($sort = $this->getScopeValue('default_sorting/' . $path, $store)) {
                $defaultSorting[] = $sort;
            }
        }

        return $defaultSorting;
    }

    public function isElasticSort(bool $skipStoreCheck = false)
    {
        return version_compare($this->magentoVersion->get(), '2.3.2', '>=')
            && strpos($this->scopeConfig->getValue(EngineInterface::CONFIG_ENGINE_PATH), 'elast') !== false
            && ($skipStoreCheck || $this->storeManager->getStore()->getId());
    }

    public function getSenseiAttributesCodes()
    {
        $result = [
            'created_at',
            $this->getScopeValue('bestsellers/best_attr'),
            $this->getScopeValue('most_viewed/viewed_attr'),
            $this->getScopeValue('new/new_attr')
        ];

        return array_filter($result);
    }

    public function getNonImageLast($storeId = null)
    {
        return (int) $this->getScopeValue('general/no_image_last', $storeId);
    }

    public function getOutOfStockLast($storeId = null)
    {
        return (int) $this->getScopeValue('general/out_of_stock_last', $storeId);
    }

    public function isOutOfStockByQty($storeId = null)
    {
        return (bool) $this->getScopeValue('general/out_of_stock_qty', $storeId);
    }

    public function getCurrentStoreId()
    {
        return (int) $this->storeManager->getStore()->getId();
    }

    public function isCatalogProductFlatEnabled()
    {
        return $this->scopeConfig->getValue(
            \Magento\Catalog\Model\Indexer\Product\Flat\State::INDEXER_ENABLED_XML_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getCurrentStoreId()
        );
    }
}
