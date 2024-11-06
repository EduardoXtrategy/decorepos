<?php

namespace Uzer\Xnotify\Block\Product;

use Magento\Customer\Model\Session;

class View extends \Magento\Catalog\Block\Product\View
{

    public function getCustomerSession(): Session
    {
        return $this->customerSession;
    }


}
