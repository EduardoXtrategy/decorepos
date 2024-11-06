<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Helper\Data;
use Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapperInterface;
use Sensei\SortingPro\Model\Elasticsearch\SkuRegistry;
use Sensei\SortingPro\Model\ResourceModel\Inventory;
use Magento\Store\Model\StoreManagerInterface;

class Stock implements DataMapperInterface
{

    private $data;

    private $inventory;

    private $storeManager;

    private $skuRegistry;

    public function __construct(
        Data $data,
        Inventory $inventory,
        StoreManagerInterface $storeManager,
        SkuRegistry $skuRegistry
    ) {
        $this->data = $data;
        $this->inventory = $inventory;
        $this->storeManager = $storeManager;
        $this->skuRegistry = $skuRegistry;
    }

    public function map($entityId, array $entityIndexData, $storeId, $context = [])
    {
        $sku = $this->skuRegistry->getSku((int) $entityId);

        if (!$sku) {
            return ['out_of_stock_last' => true];
        }

        if ($this->data->isOutOfStockByQty($storeId)) {
            $currentQty = $this->inventory->getQty(
                $sku,
                $this->storeManager->getStore($storeId)->getWebsite()->getCode()
            );
            $value = (int) ($currentQty > $this->data->getQtyOutStock($storeId));
        } else {
            $value = (int) $this->inventory->getStockStatus(
                $sku,
                $this->storeManager->getStore($storeId)->getWebsite()->getCode()
            );
        }

        return ['out_of_stock_last' => $value];
    }

    public function isAllowed($storeId)
    {
        return $this->data->getOutOfStockLast($storeId);
    }
}
