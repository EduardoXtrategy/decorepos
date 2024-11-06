<?php

namespace Uzer\Catalog\Block\Product\Renderer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Uzer\Catalog\Helper\StockProduct;

class Simple extends \Magento\Catalog\Block\Product\View
{

    protected StockProduct $stockProduct;
    protected array $data = [];

    public function __construct(
        \Magento\Catalog\Block\Product\Context              $context,
        \Magento\Framework\Url\EncoderInterface             $urlEncoder,
        \Magento\Framework\Json\EncoderInterface            $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils               $string,
        \Magento\Catalog\Helper\Product                     $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface           $localeFormat,
        \Magento\Customer\Model\Session                     $customerSession,
        ProductRepositoryInterface                          $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface   $priceCurrency,
        StockProduct                                        $stockProduct,
        array                                               $data = []
    )
    {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );

        $this->stockProduct = $stockProduct;
    }

    protected function _beforeToHtml(): Simple
    {
        $this->data = $this->stockProduct->getQty($this->getProduct());
        return parent::_beforeToHtml();
    }

    public function getQty()
    {
        return $this->data['qty'];
    }

    public function getAvailableBoxes()
    {
        return $this->data['available_boxes'];
    }

    /**
     * @return false|string
     */
    public function getAllData()
    {
        return json_encode($this->data);
    }
}
