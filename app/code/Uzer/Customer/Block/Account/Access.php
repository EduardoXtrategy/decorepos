<?php

namespace Uzer\Customer\Block\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory as BussinesCollectionFactory;

class Access extends Template
{

    protected CollectionFactory $collectionFactory;
    protected BussinesCollectionFactory $businessCollectionFactory;
    protected Session $session;

    public function __construct(
        Context                   $context,
        CollectionFactory         $collectionFactory,
        BussinesCollectionFactory $businessCollectionFactory,
        Session                   $session,
        array                     $data = []
    )
    {
        parent::__construct($context, $data);
        $this->session = $session;
        $this->businessCollectionFactory = $businessCollectionFactory;
    }


    public function displaySubscription(): bool
    {
        $item = $this->businessCollectionFactory
            ->create()
            ->addFieldToFilter('customers_id', array('eq' => $this->session->getCustomerId()))
            ->load()
            ->getFirstItem();
        return !$item->hasData();
    }

    public function getContactUsUrl(): string
    {
        return $this->_urlBuilder->getUrl('contact-us');
    }

}
