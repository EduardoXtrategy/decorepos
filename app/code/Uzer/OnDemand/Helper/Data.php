<?php

namespace Uzer\OnDemand\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data  extends AbstractHelper
{

    public function getEmails($storeId = null)
    {
        $value = $this->scopeConfig->getValue('ondemand/configuration/emails', ScopeInterface::SCOPE_STORE, $storeId);
        return explode(',', $value);
    }

    public function getEmailsPresale($storeId = null)
    {
        $value = $this->scopeConfig->getValue('ondemand/configuration/emails_presale', ScopeInterface::SCOPE_STORE, $storeId);
        return explode(',', $value);
    }


    public function getEmailTemplateId($storeId = null)
    {
        return $this->scopeConfig->getValue('ondemand/configuration/template', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getEmailTemplatePresaleId($storeId = null)
    {
        return $this->scopeConfig->getValue('ondemand/configuration/template_presale', ScopeInterface::SCOPE_STORE, $storeId);
    }


    public function getOnDemandText($storeId = null)
    {
        return $this->scopeConfig->getValue('ondemand/configuration/ondemand', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getPreSaleText($storeId = null)
    {
        return $this->scopeConfig->getValue('ondemand/configuration/presale', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getOnDemandAttribute($storeId = null){
        return $this->scopeConfig->getValue('ondemand/configuration/ondemand_id', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getPreSaleAttribute($storeId = null){
        return $this->scopeConfig->getValue('ondemand/configuration/presale_id', ScopeInterface::SCOPE_STORE, $storeId);
    }
}
