<?php

namespace Uzer\Catalog\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Json\EncoderInterface as JsonEncoder;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;
use Uzer\Catalog\Helper\StockProduct;

class PartialBoxesSimple extends PartialBoxes
{
    protected StockProduct $stockProduct;
    protected array $data = [];

    public function __construct(
        Context                    $context,
        EncoderInterface           $urlEncoder,
        JsonEncoder                $jsonEncoder,
        StringUtils                $string,
        Product                    $productHelper,
        ConfigInterface            $productTypeConfig,
        FormatInterface            $localeFormat,
        Session                    $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface     $priceCurrency,
        StockProduct               $stockProduct,
        array                      $data = []
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


    protected function _beforeToHtml(): PartialBoxesSimple
    {
        $this->data = $this->stockProduct->getQty($this->getProduct());
        return parent::_beforeToHtml();
    }

    public function getPartialBoxQty()
    {
        return $this->data['partial_boxes'];
    }

}
