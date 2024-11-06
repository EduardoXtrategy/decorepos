<?php

namespace Uzer\CustomProducts\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public function parentCategory($storeId = null)
    {
        return $this->scopeConfig->getValue('uzer/customers/parent_category', ScopeInterface::SCOPE_STORE, $storeId);
    }
}
