<?php

namespace Uzer\Location\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Uzer\Location\Helper\Data;

class Product extends \Magento\Catalog\Block\Product\View
{

    private Data $helperData;

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
        \Uzer\Location\Helper\Data                          $helperData,
        array                                               $data = []
    )
    {
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
        $this->helperData = $helperData;
    }

    /**
     * @return Data
     */
    public function getHelperData(): Data
    {
        return $this->helperData;
    }


    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Validate if is available for current store
     * @param $attributeValue
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function validate($attributeValue): bool
    {
        $option = $this->getHelperData()->getAttributeValue($this->getStoreId());
        $items = explode(',', $attributeValue);
        $isValid = false;
        foreach ($items as $item) {
            if (trim($item) == trim($option)) {
                $isValid = true;
                break;
            }
        }
        return $isValid;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRelatedStore()
    {
        return $this->helperData->getRelated($this->getStoreId());
    }

}
