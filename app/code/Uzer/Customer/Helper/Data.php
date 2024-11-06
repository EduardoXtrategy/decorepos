<?php

namespace Uzer\Customer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper {

    public function emailTemplateId($storeId = null) {
        return $this->scopeConfig->getValue('uzer/customers/approved', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function group($storeId = null) {
        return $this->scopeConfig->getValue('uzer/customers/group', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getExcludedGroup($storeId = null) {
        return $this->scopeConfig->getValue('uzer/customers/excluded_group', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function noApproved($storeId = null) {
        return $this->scopeConfig->getValue('uzer/customers/no_group', ScopeInterface::SCOPE_STORE, $storeId);
    }

}
