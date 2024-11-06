<?php

namespace Uzer\Checkoutstep\Block\Item\Price;

use Magento\Directory\Model\Currency;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem as QuoteItem;
use Magento\Store\Model\ScopeInterface;
use Mageplaza\CurrencyFormatter\Helper\Data;
use Uzer\Catalog\Plugin\Model\QuoteWrapper;
use Uzer\Checkoutstep\Model\PriceCurrency;
use Magento\Sales\Model\Order\Item as OrderItem;

class Renderer extends \Magento\Weee\Block\Item\Price\Renderer
{

    protected FormatInterface $_localeFormat;
    protected CurrencyInterface $_localeCurrency;
    protected Data $mageplazaHelperData;
    private PriceCurrency $customPriceCurrency;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Tax\Helper\Data                         $taxHelper,
        PriceCurrencyInterface                           $priceCurrency,
        \Magento\Weee\Helper\Data                        $weeeHelper,
        FormatInterface                                  $_localeFormat,
        CurrencyInterface                                $_localeCurrency,
        Data                                             $mageplazaHelperData,
        PriceCurrency                                    $customPriceCurrency,
        array                                            $data = []
    )
    {
        parent::__construct($context, $taxHelper, $priceCurrency, $weeeHelper, $data);
        $this->_localeFormat = $_localeFormat;
        $this->_localeCurrency = $_localeCurrency;
        $this->mageplazaHelperData = $mageplazaHelperData;
        $this->customPriceCurrency = $customPriceCurrency;
    }

    /**
     * @throws NoSuchEntityException
     * @throws \Zend_Currency_Exception
     * @throws \Zend_Locale_Exception
     */
    public function formatPrice($price): string
    {
        $item = $this->getItem();
        if ($item instanceof QuoteItem || $item instanceof OrderItem) {
            /** @var Currency $currency */
            $currency = $this->priceCurrency->getCurrency(ScopeInterface::SCOPE_STORE);
            $currencyConfig = $this->mageplazaHelperData->getCurrencyConfig($currency->getCode(), $this->_storeManager->getStore()->getId());
            if (!is_numeric($price)) {
                $price = $this->_localeFormat->getNumber($price);
            }
            $symbol = $currencyConfig['symbol'];
            $decimals = 2;
            $decimalSeparator = $currencyConfig['decimal_separator'];
            $thousandsSeparator = $currencyConfig['group_separator'];
            $formatted = number_format($price, $decimals, $decimalSeparator, $thousandsSeparator);
            return sprintf('<span class="price">%s%s</span>', $symbol, $formatted);
        }
        return parent::formatPrice($price);
    }

}
