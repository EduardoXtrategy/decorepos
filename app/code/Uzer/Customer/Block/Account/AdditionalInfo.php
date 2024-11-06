<?php

namespace Uzer\Customer\Block\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class AdditionalInfo extends Template
{
    protected Session $session;

    public function __construct(Context $context, Session $session, array $data = [])
    {
        parent::__construct($context, $data);
        $this->session = $session;
    }


}
