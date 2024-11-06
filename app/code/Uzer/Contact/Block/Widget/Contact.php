<?php

namespace Uzer\Contact\Block\Widget;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Contact extends Template implements BlockInterface
{
    protected $_template = "Uzer_Contact::widgets/contact.phtml";

    private FormKey $formKey;

    public function __construct(Template\Context $context, FormKey $formKey, array $data = [])
    {
        parent::__construct($context, $data);
        $this->formKey = $formKey;
    }


    /**
     * get form key
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }



}
