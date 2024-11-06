<?php

namespace Uzer\Customer\Observer;

use Uzer\Customer\Model\CustomerCustomInfo;

class ForgotPasswordObserver
{
    protected CustomerCustomInfo $customerCustomInfo;

    /**
     * @param CustomerCustomInfo $customerCustomInfo
     */
    public function __construct(CustomerCustomInfo $customerCustomInfo)
    {
        $this->customerCustomInfo = $customerCustomInfo;
    }
}
