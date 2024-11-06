<?php

namespace Uzer\DeliveryLatam\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_DELIVERY_LATAM_ENABLED = 'uzer_deliverylatam/settings/active';
    const XML_PATH_DELIVERY_LATAM_PRICE = 'uzer_deliverylatam/settings/price';

    public function isDeliveryLatamEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DELIVERY_LATAM_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getDeliveryLatamPrice($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DELIVERY_LATAM_PRICE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
