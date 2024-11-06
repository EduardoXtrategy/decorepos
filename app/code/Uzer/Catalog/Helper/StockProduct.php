<?php

namespace Uzer\Catalog\Helper;

use Magento\Catalog\Model\Product;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Core\Logger\Logger;

class StockProduct
{
    protected GetProductSalableQtyInterface $getProductSalableQty;
    protected StockRepositoryInterface $stockRepository;
    protected StoreManagerInterface $storeManager;
    protected Logger $logger;

    public function __construct(
        GetProductSalableQtyInterface $getProductSalableQty,
        StockRepositoryInterface      $stockRepository,
        StoreManagerInterface         $storeManager,
        Logger                        $logger
    )
    {
        $this->getProductSalableQty = $getProductSalableQty;
        $this->stockRepository = $stockRepository;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }


    public function getQty(Product $product): array
    {
        $qty = 0;
        $boxSize = $product->getBoxSize();
        try {
            $stocks = $this->stockRepository->getList();
            foreach ($stocks->getItems() as $stock) {
                $query = false;
                foreach ($stock->getExtensionAttributes()->getSalesChannels() as $salesChannel) {
                    $query = $salesChannel->getCode() == $this->storeManager->getWebsite()->getCode();
                }
                if ($query) {
                    $qty += $this->getProductSalableQty->execute($product->getSku(), $stock->getStockId());
                }
            }
        } catch (\Exception $ex) {
            $this->logger->info('Error consulting product: ' . $product->getSku());
        }
        $divisibleBoxSize = $boxSize && $boxSize > 0 ? $boxSize : 1;
        $availableBoxes = (int)($qty > 0 ? ($qty / $divisibleBoxSize ?? 1) : 0);
        $partialBoxes = 0;
        if ($boxSize > 0) {
            $this->logger->info('Available boxes: ' . $availableBoxes);
            if ($availableBoxes >= 1) {
                $partialBoxes = $qty - ($availableBoxes * $boxSize);
            } else if ($qty > 0 && $qty < $boxSize) {
                $partialBoxes = $qty;
            }
        }
        return [
            'qty' => $qty,
            'box_size' => $boxSize,
            'available_boxes' => (int)$availableBoxes,
            'sku' => $product->getSku(),
            'partial_boxes' => $partialBoxes
        ];
    }

}
