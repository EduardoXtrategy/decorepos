<?php

namespace Uzer\Theme\Plugin\ConfigurableProduct\Block\Product\View\Type;


use Magento\Catalog\Model\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Theme\Helper\Data;
use Uzer\Theme\Model\MakeTooltipContent;

class Configurable
{


    protected StoreManagerInterface $storeManager;
    protected MakeTooltipContent $makeTooltipContent;
    protected Data $data;

    /**
     * @param StoreManagerInterface $storeManager
     * @param MakeTooltipContent $makeTooltipContent
     * @param Data $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        MakeTooltipContent    $makeTooltipContent,
        Data                  $data
    )
    {
        $this->storeManager = $storeManager;
        $this->makeTooltipContent = $makeTooltipContent;
        $this->data = $data;
    }


    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
                                                                          $result
    )
    {
        $jsonResult = json_decode($result, true);
        $hasHeader = false;
        foreach ($jsonResult['attributes'] as $attribute) {
            if ($attribute['code'] == 'header') {
                $hasHeader = true;
                break;
            }
        }
        $jsonResult['hasHeader'] = $hasHeader;
        if ($this->data->isEnable($this->storeManager->getStore()->getId()) && $hasHeader) {
            try {
                $jsonResult['headerContent'] = $this->makeTooltipContent->gateCompiledCode();
            } catch (\Exception $ex) {
            }
        }
        $jsonResult['skus'] = [];
        $objectManager = ObjectManager::getInstance();
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $product = $objectManager->create(Product::class)->load($simpleProduct->getId());
            $jsonResult['skus'][$simpleProduct->getId()]['sku'] = $simpleProduct->getSku();
            $jsonResult['skus'][$simpleProduct->getId()]['box_quantity'] = $product->getBoxQuantity();
            $jsonResult['skus'][$simpleProduct->getId()]['marketing_color'] = $product->getMarketingColor();
            $jsonResult['skus'][$simpleProduct->getId()]['perforation'] = $product->getPerforation();
            $jsonResult['skus'][$simpleProduct->getId()]['is_new'] = $this->isNew($simpleProduct);
            $jsonResult['skus'][$simpleProduct->getId()]['sale'] = $simpleProduct->getDiscount();
            $jsonResult['skus'][$simpleProduct->getId()]['available_message'] = $product->getAvailableMessage();
            $jsonResult['skus'][$simpleProduct->getId()]['sustainable'] = $product->getSustainable();
        }
        return json_encode($jsonResult);
    }

    public function isNew(Product $product): bool
    {
        $todayDate = time();
        $timeStartNew = strtotime($product->getData('news_from_date'));
        $timeEndNew = strtotime($product->getData('news_to_date'));
        return $timeEndNew >= $todayDate && $timeStartNew <= $todayDate;
    }

}
