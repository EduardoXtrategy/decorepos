<?php

namespace Uzer\Checkoutstep\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_CUSTOM_FIELD = 'uzer_checkout/checkout/cart';

    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getTextMessage($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CUSTOM_FIELD, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
