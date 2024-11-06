<?php


namespace Uzer\Samples\Block\Product;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProduct;

class View extends \Magento\Catalog\Block\Product\View
{

    private Configurable $configurable;

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
        Configurable                                        $configurable,
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
        $this->configurable = $configurable;
    }

    public function getAttributes()
    {
        $jsonConfig = json_decode($this->configurable->getJsonConfig(), true);
        if (isset($jsonConfig['attributes'])) {
            return $jsonConfig['attributes'];
        }
        return [];
    }

    public function isSample()
    {
        return $this->getProduct()->getIsSample();
    }

    public function displayForm(): bool
    {
        if ($this->getProduct()->getTypeId() == ConfigurableProduct::TYPE_CODE) {
            return true;
        }
        return false;
    }
}
