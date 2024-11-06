<?php

namespace Uzer\Contact\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    public function emailTemplateId($storeId = null)
    {
        return $this->scopeConfig->getValue('decowraps/contact/notification', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getEmails($storeId = null)
    {
        $value = $this->scopeConfig->getValue('decowraps/contact/emails', ScopeInterface::SCOPE_STORE, $storeId);
        return explode(',', $value);
    }
}
