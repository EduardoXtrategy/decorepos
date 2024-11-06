<?php

namespace Uzer\Samples\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    public function getEmails($storeId = null)
    {
        $value = $this->scopeConfig->getValue('samples/configuration/emails', ScopeInterface::SCOPE_STORE, $storeId);
        return explode(',', $value);
    }

    public function emailTemplateId($storeId = null)
    {
        return $this->scopeConfig->getValue('samples/configuration/template', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function kitEmailTemplateId($storeId = null)
    {
        return $this->scopeConfig->getValue('samples/configuration/kit', ScopeInterface::SCOPE_STORE, $storeId);
    }

}
