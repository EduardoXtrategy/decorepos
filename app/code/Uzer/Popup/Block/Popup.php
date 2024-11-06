<?php
namespace Uzer\Popup\Block;

class Popup extends \Magento\Framework\View\Element\Template
{
    const POPUP_BLOCK_ID = 'simplepopupwindow/general/block_id';

    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
    }
   
    public function getBlockId()
    {
        return $this->_scopeConfig->getValue(self::POPUP_BLOCK_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
