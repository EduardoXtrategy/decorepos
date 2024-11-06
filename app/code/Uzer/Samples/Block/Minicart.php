<?php

namespace Uzer\Samples\Block;

use Magento\Framework\View\Element\Template;
use Uzer\Samples\Model\Session;

class Minicart extends Template
{

    private Session $session;

    public function __construct(Template\Context $context, \Uzer\Samples\Model\Session $session, array $data = [])
    {
        parent::__construct($context, $data);
        $this->session = $session;
    }


    public function getCartQty(): int
    {
        if ($this->session->hasSamplesCart()) {
            $samplesCart = $this->session->getSamplesCart();
            return $samplesCart->getQtyItems();
        }
        return 0;
    }


}
