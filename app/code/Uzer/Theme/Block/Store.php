<?php

namespace Uzer\Theme\Block;

use Magento\Framework\View\Element\Template;

class Store extends Template
{

    protected $_template = 'Uzer_Theme::store.phtml';

    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }


}
