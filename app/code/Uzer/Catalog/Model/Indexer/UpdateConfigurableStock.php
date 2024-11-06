<?php

namespace Uzer\Catalog\Model\Indexer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogInventory\Api\StockItemRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Validation\ValidationException;

class UpdateConfigurableStock implements ActionInterface, \Magento\Framework\Mview\ActionInterface
{

    private ProductCollectionFactory $productCollectionFactory;
    private StockItemRepositoryInterface $stockItemRepository;
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductCollectionFactory $productCollectionFactory
     * @param StockItemRepositoryInterface $stockItemRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductCollectionFactory     $productCollectionFactory,
        StockItemRepositoryInterface $stockItemRepository,
        ProductRepositoryInterface   $productRepository
    )
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->stockItemRepository = $stockItemRepository;
        $this->productRepository = $productRepository;
    }


    /**
     * @throws ValidationException
     * @throws CouldNotSaveException
     * @throws InputException
     */
    public function executeFull()
    {
        $productCollection = $this->productCollectionFactory->create();
        /** @var \Magento\Catalog\Model\Product[] $products */
        $products = $productCollection->addAttributeToSelect('*')->addAttributeToFilter('type_id', array('eq' => 'configurable'))->load()->getItems();
        foreach ($products as $product) {
            $productInterface = $this->productRepository->get($product->getSku());
            if ($productInterface->getTypeId() == 'configurable') {
                $stockItem = $productInterface->getExtensionAttributes()->getStockItem();
                if ($stockItem && !$stockItem->getIsInStock()) {
                    $stockItem->setIsInStock(true);
                    $this->stockItemRepository->save($stockItem);
                }
            }
        }
    }

    public function executeList(array $ids)
    {
        $this->executeFull();
    }

    public function executeRow($id)
    {
        $this->executeFull();
    }

    public function execute($ids)
    {
        $this->executeFull();
    }
}
