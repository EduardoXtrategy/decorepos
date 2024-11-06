<?php

namespace Uzer\Catalog\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Json\EncoderInterface as JsonEncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;

class View extends \Magento\Catalog\Block\Product\View
{
    public function __construct(
        Context                    $context,
        EncoderInterface           $urlEncoder,
        JsonEncoderInterface       $jsonEncoder,
        StringUtils                $string,
        Product                    $productHelper,
        ConfigInterface            $productTypeConfig,
        FormatInterface            $localeFormat,
        Session                    $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface     $priceCurrency,
        array                      $data = [])
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
    }


    public function displayAddToCart(): bool
    {
        return true;
    }

    public function availableAddToCart(): bool
    {
        return true;
    }

}
