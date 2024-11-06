<?php

namespace Uzer\Checkoutstep\Model;

use Magento\Directory\Model\Currency;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\CurrencyFormatter\Helper\Data;

class CustomFormat
{
    protected FormatInterface $_localeFormat;
    protected CurrencyInterface $_localeCurrency;
    protected Data $mageplazaHelperData;
    protected PriceCurrency $customPriceCurrency;
    protected StoreManagerInterface $_storeManager;
    protected PriceCurrencyInterface $priceCurrency;

    /**
     * @param FormatInterface $_localeFormat
     * @param CurrencyInterface $_localeCurrency
     * @param Data $mageplazaHelperData
     * @param PriceCurrency $customPriceCurrency
     * @param StoreManagerInterface $_storeManager
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        FormatInterface        $_localeFormat,
        CurrencyInterface      $_localeCurrency,
        Data                   $mageplazaHelperData,
        PriceCurrency          $customPriceCurrency,
        StoreManagerInterface  $_storeManager,
        PriceCurrencyInterface $priceCurrency
    )
    {
        $this->_localeFormat = $_localeFormat;
        $this->_localeCurrency = $_localeCurrency;
        $this->mageplazaHelperData = $mageplazaHelperData;
        $this->customPriceCurrency = $customPriceCurrency;
        $this->_storeManager = $_storeManager;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function format($price)
    {
        return $this->customFormat($price, 2);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function formatLong($price)
    {
        return $this->customFormat($price, 4);
    }

    public function customFormat($price, $decimals = 2)
    {
        /** @var Currency $currency */
        $currency = $this->priceCurrency->getCurrency(ScopeInterface::SCOPE_STORE);
        $currencyConfig = $this->mageplazaHelperData->getCurrencyConfig($currency->getCode(), $this->_storeManager->getStore()->getId());
        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }
        $symbol = $currencyConfig['symbol'];
        $decimalSeparator = $currencyConfig['decimal_separator'];
        $thousandsSeparator = $currencyConfig['group_separator'];
        $formatted = number_format($price, $decimals, $decimalSeparator, $thousandsSeparator);
        return sprintf('%s%s', $symbol, $formatted);
    }

}
