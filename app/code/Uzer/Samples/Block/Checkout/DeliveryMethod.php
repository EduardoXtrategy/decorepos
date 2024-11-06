<?php

namespace Uzer\Samples\Block\Checkout;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as customerSession;
use Uzer\Samples\Block\Cart\BaseBlock;

class DeliveryMethod extends BaseBlock
{
    protected customerSession $customerSession;

    public function __construct(
        Template\Context $context,
        CurrencyFactory  $currencyFactory,
        customerSession  $customerSession,
        array            $data = [])
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->customerSession = $customerSession;
    }

    public function getSessionCustomer()
    {

        return $this->customerSession->isLoggedIn();
    }
}
