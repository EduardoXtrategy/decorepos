<?php

namespace Uzer\Samples\Block\Cart;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\View\Element\Template;

class BaseBlock extends Template
{

    protected CurrencyFactory $currencyFactory;
    protected ?string $currencyCode = null;

    public function __construct(Template\Context $context, CurrencyFactory $currencyFactory, array $data = [])
    {
        parent::__construct($context, $data);
        $this->currencyFactory = $currencyFactory;
    }


    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencySymbol(): ?string
    {
        if (is_null($this->currencyCode)) {
            $currencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();
            $this->currencyCode = $this->currencyFactory->create()->load($currencyCode)->getCurrencySymbol();
        }
        return $this->currencyCode;
    }

}
