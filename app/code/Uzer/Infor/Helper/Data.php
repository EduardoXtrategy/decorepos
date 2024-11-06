<?php

namespace Uzer\Infor\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    private static string $configPath = 'uzer/infor/';

    private function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::$configPath . $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }


    public function getAccessTokenUrl($storeId = null)
    {
        return $this->getConfigValue('access_token_url', $storeId);
    }

    public function getClientId($storeId = null)
    {
        return $this->getConfigValue('client_id', $storeId);
    }

    public function getClientSecret($storeId = null)
    {
        return $this->getConfigValue('client_secret', $storeId);
    }

    public function getUsername($storeId = null)
    {
        return $this->getConfigValue('username', $storeId);
    }

    public function getPassword($storeId = null)
    {
        return $this->getConfigValue('password', $storeId);
    }

    public function getApiUrl($storeId = null)
    {
        return $this->getConfigValue('api_url', $storeId);
    }

    public function getTenantId($storeId = null)
    {
        return $this->getConfigValue('tenant_id', $storeId);
    }

    public function getIdo($storeId = null)
    {
        return $this->getConfigValue('ido', $storeId);
    }

    public function getMoongooseConfig($storeId = null)
    {
        return $this->getConfigValue('moongoose_config', $storeId);
    }


}
