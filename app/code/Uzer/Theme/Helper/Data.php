<?php

namespace Uzer\Theme\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    public function isEnable($storeId = null): bool
    {
        $value = $this->scopeConfig->getValue('theme/general/enable_frontend', ScopeInterface::SCOPE_STORE, $storeId);
        return $value == 1 || $value == true;
    }


    public function getTooltip($storeId = null)
    {
        return $this->scopeConfig->getValue('theme/general/header_tooltip', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getEuUrl($storeId = null)
    {
        return $this->scopeConfig->getValue('theme/general/eu_url', ScopeInterface::SCOPE_STORE, $storeId);
    }

}
