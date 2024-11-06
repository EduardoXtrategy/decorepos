<?php

namespace Uzer\AdvancedFilter\Model\Layer\Filter;

use Amasty\Shopby\Helper\FilterSetting;
use Amasty\Shopby\Model\Request;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Search\EngineResolverInterface;
use Magento\Search\Api\SearchInterface;
use Magento\Store\Model\StoreManagerInterface;

class Stock extends \Amasty\Shopby\Model\Layer\Filter\Stock
{

    private Request $shopbyRequest;

    public function __construct(
        ItemFactory                 $filterItemFactory,
        StoreManagerInterface       $storeManager,
        Layer                       $layer,
        DataBuilder                 $itemDataBuilder,
        ScopeConfigInterface        $scopeConfig,
        Request                     $shopbyRequest,
        StockConfigurationInterface $stockConfiguration,
        SearchInterface             $search,
        FilterSetting               $settingHelper,
        EngineResolverInterface     $engineResolver,
        array                       $data = []
    )
    {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $scopeConfig,
            $shopbyRequest,
            $stockConfiguration,
            $search,
            $settingHelper,
            $engineResolver,
            $data
        );
        $this->shopbyRequest = $shopbyRequest;
    }


    /**
     * @return bool
     */
    private function isHide(): bool
    {
        return (bool)$this->shopbyRequest->getFilterParam($this) && !$this->isVisibleWhenSelected();
    }

    protected function _getItemsData(): array
    {
        if ($this->isHide()) {
            return [];
        }

        $optionsFacetedData = $this->getFacetedData();

        $inStock = isset($optionsFacetedData[self::FILTER_IN_STOCK])
            ? $optionsFacetedData[self::FILTER_IN_STOCK]['count'] : 1;
//        $outStock = isset($optionsFacetedData[$this->filterOutStock])
//            ? $optionsFacetedData[$this->filterOutStock]['count'] : 0;
//

        $listData = [
            [
                'label' => __('In Stock'),
                'value' => self:: FILTER_IN_STOCK,
                'count' => $inStock,
            ],
//            [
//                'label' => __('Out of Stock'),
//                'value' => self:: FILTER_OUT_OF_STOCK,
//                'count' => $outStock,
//            ]
        ];

        foreach ($listData as $data) {
            if ($data['count'] < 1) {
                continue;
            }
            $this->itemDataBuilder->addItemData(
                $data['label'],
                $data['value'],
                $data['count']
            );
        }

        return $this->itemDataBuilder->build();
    }

}
