<?php

namespace Sensei\SortingPro\Model\ResourceModel;

class Inventory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    private $stockIds;
    private $sourceCodes;
    private $qty;
    private $stockStatus;
    private $moduleManager;
    private $stockRegistry;

    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->moduleManager = $moduleManager;
        $this->stockRegistry = $stockRegistry;
    }

    protected function _construct()
    {
        $this->stockIds = [];
        $this->sourceCodes = [];
        $this->qty = [];
    }

    public function getQty($productSku, $websiteCode)
    {
        if ($this->moduleManager->isEnabled('Magento_Inventory')) {
            $qty = $this->getMsiQty($productSku, $websiteCode);
        } else {
            $qty = $this->getStockItem($productSku, $websiteCode)->getQty();
        }

        return $qty;
    }

    public function getStockStatus($productSku, $websiteCode)
    {
        if ($this->moduleManager->isEnabled('Magento_Inventory')) {
            $stockStatus = $this->getMsiStock($productSku, $websiteCode);
        } else {
            $stockStatus = $this->getStockItem($productSku, $websiteCode)->getIsInStock();
        }

        return $stockStatus;
    }


    private function getStockItem($productSku, $websiteCode)
    {
        return $this->stockRegistry->getStockItemBySku($productSku, $websiteCode);
    }


    public function getMsiStock($productSku, $websiteCode)
    {
        if (!isset($this->stockStatus[$websiteCode][$productSku])) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('inventory_stock_' . $this->getStockId($websiteCode)), ['is_salable'])
                ->where('sku = ?', $productSku)
                ->group('sku');
            $this->stockStatus[$websiteCode][$productSku] = (int) $this->getConnection()->fetchOne($select);
        }

        return $this->stockStatus[$websiteCode][$productSku];
    }


    public function getMsiQty($productSku, $websiteCode)
    {
        if (!isset($this->qty[$websiteCode][$productSku])) {
            $this->qty[$websiteCode][$productSku] = $this->getItemQty($productSku, $websiteCode)
                + $this->getReservationQty($productSku, $this->getStockId($websiteCode));
        }

        return $this->qty[$websiteCode][$productSku];
    }

    private function getItemQty($productSku, $websiteCode)
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable('inventory_source_item'), ['SUM(quantity)'])
            ->where('source_code IN (?)', $this->getSourceCodes($websiteCode))
            ->where('sku = ?', $productSku)
            ->group('sku');

        return $this->getConnection()->fetchOne($select);
    }

    public function getStockId($websiteCode)
    {
        if (!isset($this->stockIds[$websiteCode])) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('inventory_stock_sales_channel'), ['stock_id'])
                ->where('type = \'website\' AND code = ?', $websiteCode);

            $this->stockIds[$websiteCode] = (int)$this->getConnection()->fetchOne($select);
        }

        return $this->stockIds[$websiteCode];
    }

    public function getSourceCodes($websiteCode)
    {
        if (!isset($this->sourceCodes[$websiteCode])) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('inventory_source_stock_link'), ['source_code'])
                ->where('stock_id = ?', $this->getStockId($websiteCode));

            $this->sourceCodes[$websiteCode] = $this->getConnection()->fetchCol($select);
        }

        return $this->sourceCodes[$websiteCode];
    }

    private function getReservationQty($sku, $stockId)
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable('inventory_reservation'), ['quantity' => 'SUM(quantity)'])
            ->where('sku = ?', $sku)
            ->where('stock_id = ?', $stockId)
            ->limit(1);

        $reservationQty = $this->getConnection()->fetchOne($select);
        if ($reservationQty === false) {
            $reservationQty = 0;
        }

        return $reservationQty;
    }
}
