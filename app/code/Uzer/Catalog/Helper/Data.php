<?php

namespace Uzer\Catalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    public function isEnable($storeId = null): bool
    {
        $value = $this->scopeConfig->getValue('boxes/configuration/enable', ScopeInterface::SCOPE_STORE, $storeId);
        return $value == 1 || $value == true;
    }

}
