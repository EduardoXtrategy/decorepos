<?php


namespace Uzer\Samples\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Form extends Template implements BlockInterface
{
    protected $_template = "Uzer_Samples::widgets/form.phtml";
}
