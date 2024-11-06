<?php


namespace Uzer\Location\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    public function getEnabled($storeId = null): bool
    {
        return $this->scopeConfig->getValue('location/stores/enabled', ScopeInterface::SCOPE_STORE, $storeId) == 1;
    }

    public function getRelated($storeId = null)
    {
        return $this->scopeConfig->getValue('location/stores/related', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getAttributeValue($storeId = null)
    {
        return $this->scopeConfig->getValue('location/stores/attribute_value', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getMessage($storeId = null)
    {
        return $this->scopeConfig->getValue('location/stores/message', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getTextLink($storeId = null)
    {
        return $this->scopeConfig->getValue('location/stores/textlink', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getPathImage($storeId = null)
    {
        return $this->scopeConfig->getValue('location/stores/image', ScopeInterface::SCOPE_STORE, $storeId);
    }
}
