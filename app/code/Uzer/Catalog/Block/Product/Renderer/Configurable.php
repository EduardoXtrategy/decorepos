<?php

namespace Uzer\Catalog\Block\Product\Renderer;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product as CatalogProduct;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\ConfigurableProduct\Helper\Data;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\SwatchAttributesProvider;

class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{
    protected RequestInterface $request;

    public function __construct(
        Context                   $context,
        ArrayUtils                $arrayUtils,
        EncoderInterface          $jsonEncoder,
        Data                      $helper,
        CatalogProduct            $catalogProduct,
        CurrentCustomer           $currentCustomer,
        PriceCurrencyInterface    $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        SwatchData                $swatchHelper,
        Media                     $swatchMediaHelper,
        array                     $data = [],
        SwatchAttributesProvider  $swatchAttributesProvider = null,
        UrlBuilder                $imageUrlBuilder = null
    )
    {
        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $helper,
            $catalogProduct,
            $currentCustomer,
            $priceCurrency,
            $configurableAttributeData,
            $swatchHelper,
            $swatchMediaHelper,
            $data,
            $swatchAttributesProvider,
            $imageUrlBuilder
        );
    }


    protected function getCacheLifetime(): ?int
    {
        if ($this->_request->getFullActionName() == 'catalog_product_view') {
            return null;
        }
        return parent::getCacheLifetime();
    }

}
