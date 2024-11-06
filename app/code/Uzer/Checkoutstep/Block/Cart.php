<?php

namespace Uzer\Checkoutstep\Block;

use Magento\Framework\View\Element\Template;

class Cart extends Template
{

    protected \Uzer\Checkoutstep\Helper\Data $helperData;

    /**
     *
     * {@inheritdoc}
     * @see \Magento\Framework\View\Element\Template::__construct()
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Uzer\Checkoutstep\Helper\Data $helperData, array $data = array())
    {
        parent::__construct($context, $data);
        $this->helperData = $helperData;
    }

    public function getMessage()
    {
        return $this->helperData->getTextMessage($this->_storeManager->getStore()->getId());
    }
}
