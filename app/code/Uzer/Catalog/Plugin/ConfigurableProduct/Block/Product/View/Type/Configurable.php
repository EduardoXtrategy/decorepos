<?php

namespace Uzer\Catalog\Plugin\ConfigurableProduct\Block\Product\View\Type;

use Magento\Catalog\Model\ResourceModel\ProductFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Uzer\Catalog\Helper\StockProduct;

class Configurable
{

    protected Json $jsonEncoder;
    protected ProductFactory $resourceModel;
    protected StockProduct $stockProduct;

    public function __construct(Json $jsonEncoder, ProductFactory $resourceModel, StockProduct $stockProduct)
    {
        $this->jsonEncoder = $jsonEncoder;
        $this->resourceModel = $resourceModel;
        $this->stockProduct = $stockProduct;
    }


    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
                                                                          $result
    )
    {
        $jsonResult = json_decode($result, true);
        $jsonResult['skus'] = [];
        $jsonResult['ids'] = [];
        $resourceModel = $this->resourceModel->create();
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $resourceModel->load($simpleProduct, $simpleProduct->getId());
            $item = $this->stockProduct->getQty($simpleProduct);
            $jsonResult['skus'][$simpleProduct->getSku()] = $item;
            $jsonResult['ids'][$simpleProduct->getId()] = $item;
        }
        return $this->jsonEncoder->serialize($jsonResult);
    }

}
