<?php

namespace Uzer\Samples\Block\Cart\Content;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\View\Element\Template;
use Uzer\Samples\Block\Cart\BaseBlock;
use Uzer\Samples\Model\SampleCartItem;
use Uzer\Samples\Model\Session;

class Summary extends BaseBlock
{

    private Session $session;
    /**
     * @var SampleCartItem[]
     *
     */
    private array $items = [];
    private bool $loaded = false;

    public function __construct(Template\Context $context, CurrencyFactory $currencyFactory, Session $session, array $data = [])
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->session = $session;
    }


    /**
     * @return SampleCartItem[]
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function getItems(): array
    {
        if (!$this->loaded)
            $this->items = $this->session->getSamplesCart()->getItems();
        return $this->items;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function hasItems(): bool
    {
        return count($this->getItems()) > 0;
    }

}
