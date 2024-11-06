<?php

namespace Uzer\Middleware\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Configuration extends AbstractHelper
{

    const BASE_PATH = 'middleware/configuration/';

    public function isEnable($storeId = null)
    {
        $value = $this->scopeConfig->getValue(self::BASE_PATH . 'enable', ScopeInterface::SCOPE_STORE, $storeId);
        return $value == 1 || $value == true;
    }

    public function getClientId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::BASE_PATH . 'client_id', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getClientSecret($storeId = null)
    {
        return $this->scopeConfig->getValue(self::BASE_PATH . 'client_secret', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getUrl($storeId = null)
    {
        return $this->scopeConfig->getValue(self::BASE_PATH . 'api_url', ScopeInterface::SCOPE_STORE, $storeId);
    }
}
