<?php

namespace Uzer\AdvancedFilter\Model\Indexer;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\ProductFactory;
use Uzer\AdvancedFilter\Model\ProductTypeSizes;
use Uzer\AdvancedFilter\Model\ProductTypeSizesFactory;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizesFactory as ResourceModelFactory;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class ProductTypeSizesIndexer implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{

    private CollectionFactory $collection;
    private ProductCollectionFactory $productCollectionFactory;
    private ProductTypeSizesFactory $productTypeSizesFactory;
    private ResourceModelFactory $resourceModelFactory;
    private ProductFactory $resourceModelProductFactory;

    /**
     * @param CollectionFactory $collection
     * @param ProductCollectionFactory $productCollectionFactory
     * @param ProductTypeSizesFactory $productTypeSizesFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param ProductFactory $resourceModelProductFactory
     */
    public function __construct(
        CollectionFactory        $collection,
        ProductCollectionFactory $productCollectionFactory,
        ProductTypeSizesFactory  $productTypeSizesFactory,
        ResourceModelFactory     $resourceModelFactory,
        ProductFactory           $resourceModelProductFactory
    )
    {
        $this->collection = $collection;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productTypeSizesFactory = $productTypeSizesFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->resourceModelProductFactory = $resourceModelProductFactory;
    }


    /**
     * @throws \Exception
     */
    public function executeFull()
    {
        $resourceModel = $this->resourceModelFactory->create();
        $this->collection->create()->deleteAll();
        $productTypeSizes = array();
        /** @var Product[] $products */
        $products = $this->productCollectionFactory->create()
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('sku')
            ->addFieldToSelect('product_type_decowraps')
            ->addFieldToSelect('size')
            ->addFieldToSelect('status')
            ->addFieldToFilter('type_id', array('eq' => 'simple'))
            ->getItems();
        foreach ($products as $product) {
            $productTypeSizes[$product->getSku()] = [
                'product_type_id' => $product->getData('product_type_decowraps'),
                'size_id' => $product->getData('size'),
                'sku' => $product->getSku(),
                'status' => $product->getStatus(),
            ];
        }
        $productTypeSizes = array_values($productTypeSizes);
        $resourceModel->saveMany($productTypeSizes);
    }

    /**
     * @param array $ids
     * @throws \Exception
     */
    public function executeList(array $ids)
    {
        $this->executeFull();
    }

    /**
     * @throws \Exception
     */
    public function executeRow($id)
    {
        $this->executeFull();
    }

    /**
     * @param int[] $ids
     * @throws \Exception
     */
    public function execute($ids)
    {
        $this->executeFull();
    }
}
