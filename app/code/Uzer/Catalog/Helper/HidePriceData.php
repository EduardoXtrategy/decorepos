<?php

namespace Uzer\Catalog\Helper;

use Magento\Store\Model\ScopeInterface;

class HidePriceData extends \Meetanshi\HidePrice\Helper\Data
{

    const XML_PATH_DISPLAY_TEXT_LIST = 'hideprice/general/displayed_text_list';

    /**
     * @return mixed
     */
    public function getHidePriceTextForNotLogged()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DISPLAY_TEXT_LIST, ScopeInterface::SCOPE_STORE);
    }

}
