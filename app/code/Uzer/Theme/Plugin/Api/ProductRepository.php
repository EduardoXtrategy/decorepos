<?php

namespace Uzer\Theme\Plugin\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductCustomOptionRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;

class ProductRepository
{

    private Config $eavConfig;
    private ProductCustomOptionRepositoryInterface $productCustomOptionRepository;

    /**
     * @param Config $eavConfig
     * @param ProductCustomOptionRepositoryInterface $productCustomOptionRepository
     */
    public function __construct(Config $eavConfig, \Magento\Catalog\Api\ProductCustomOptionRepositoryInterface $productCustomOptionRepository)
    {
        $this->eavConfig = $eavConfig;
        $this->productCustomOptionRepository = $productCustomOptionRepository;
    }


    /**
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface|Product $product
     * @param false $saveOptions
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave(ProductRepositoryInterface $subject, ProductInterface $product, bool $saveOptions = false)
    {
        $product->setOptions([]);
        $product->setHasOptions(0);
        $product->setCanSaveCustomOptions(false);
        /*  if ($product->getTypeId() == 'configurable') {
             $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
             $product = $this->addCustomizableOption($product, $objectManager, 'perforation');
         } */
        return [$product];
    }

    /**
     * @param ProductInterface $product
     * @param \Magento\Framework\App\ObjectManager $objectManager
     * @param string $attributeCode
     * @return ProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function addCustomizableOption(ProductInterface $product, \Magento\Framework\App\ObjectManager $objectManager, string $attributeCode): ProductInterface
    {
        $header = $product->getCustomAttribute($attributeCode);
        if ($header && $header->getValue() == 1) {
            $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
            $productOptions = $this->productCustomOptionRepository->getProductOptions($product);
            if ((is_null($productOptions) || count($productOptions) <= 0) && $attribute) {
                //$headerOptions = explode(',', $header->getValue());
                $options = $attribute->getSource()->getAllOptions();
                $optionValues = [];
                $counter = 0;
                foreach ($options as $option) {
                    //$value = $option['value'];
                    $label = $option['label'];
                    $optionValues[] = [
                        'record_id' => $counter,
                        'title' => $label,
                        'price' => 0,
                        'price_type' => "fixed",
                        'sort_order' => $counter,
                        'is_delete' => 0
                    ];
                }
                if (count($optionValues) > 0) {
                    $productOption = [
                        "sort_order" => 0,
                        "title" => $attribute->getDefaultFrontendLabel(),
                        "price_type" => "fixed",
                        "price" => "",
                        "type" => "drop_down",
                        "is_require" => 0,
                        "values" => $optionValues
                    ];
                    $product->setHasOptions(1);
                    $product->setCanSaveCustomOptions(true);
                    $option = $objectManager->create('\Magento\Catalog\Model\Product\Option');
                    if ($product->getId())
                        $option->setProductId($product->getId());
                    $option->setStoreId($product->getStoreId())
                        ->addData($productOption);
                    $product->addOption($option);
                }
            }
            if (!is_null($productOptions) && count($productOptions) > 1) {
                $newProductOptions = array_splice($productOptions, 0, 1);
                $product->setOptions($newProductOptions);
            }
        }
        return $product;
    }
}
