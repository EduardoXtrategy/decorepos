<?php

namespace Uzer\Sales\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    public function isEnable($storeId = null): bool
    {
        $value = $this->scopeConfig->getValue('sales/order/enable', ScopeInterface::SCOPE_STORE, $storeId);
        return $value == 1;
    }

    public function getTemplate($storeId = null)
    {
        return $this->scopeConfig->getValue('sales/order/restore', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getEmails($storeId = null)
    {
        $emails = $this->scopeConfig->getValue('sales/order/emails', ScopeInterface::SCOPE_STORE, $storeId);
        return explode(',', $emails);
    }
}
