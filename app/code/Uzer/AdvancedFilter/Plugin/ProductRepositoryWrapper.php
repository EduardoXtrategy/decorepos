<?php

namespace Uzer\AdvancedFilter\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Uzer\AdvancedFilter\Model\ProductTypeSizesFactory;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizesFactory as ResourceModelFactory;

class ProductRepositoryWrapper
{


    private ProductTypeSizesFactory $productTypeSizesFactory;
    private ResourceModelFactory $resourceModelFactory;

    /**
     * @param ProductTypeSizesFactory $productTypeSizesFactory
     * @param ResourceModelFactory $resourceModelFactory
     */
    public function __construct(
        ProductTypeSizesFactory $productTypeSizesFactory,
        ResourceModelFactory    $resourceModelFactory
    )
    {
        $this->productTypeSizesFactory = $productTypeSizesFactory;
        $this->resourceModelFactory = $resourceModelFactory;
    }


    /**
     * Intercepted method save.
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $result
     * @param ProductInterface $product
     * @param bool $saveOptions
     *
     * @return ProductInterface
     * @throws AlreadyExistsException
     * @see \Magento\Catalog\Api\ProductRepositoryInterface::save
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        ProductRepositoryInterface $subject,
        ProductInterface           $result,
        ProductInterface           $product,
        bool                       $saveOptions = false
    ): ProductInterface
    {
        $resourceModel = $this->resourceModelFactory->create();
        $productTypeSizes = $this->productTypeSizesFactory->create();
        $resourceModel->load($productTypeSizes, $product->getSku(), 'sku');
        $productTypeSizes->setData('sku', $product->getSku());
        $productTypeSizes->setData('product_type_id', $this->getAttributeValue($result, 'product_type_decowraps'));
        $productTypeSizes->setData('size', $this->getAttributeValue($result, 'size'));
        $productTypeSizes->setData('status', $product->getStatus());
        $resourceModel->save($productTypeSizes);
        return $result;
    }

    private function getAttributeValue(ProductInterface $result, string $code)
    {
        $attribute = $result->getCustomAttribute($code);
        if ($attribute) {
            return $attribute->getValue();
        }
        return null;
    }

}
