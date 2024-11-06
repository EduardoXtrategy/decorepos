<?php

namespace Uzer\Customer\Block\Register;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModel;
use Magento\Customer\Model\Session;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\Country;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory as BussinesCollectionFactory;

class Step extends Template
{

    protected BussinesCollectionFactory $collectionFactory;
    protected CollectionFactory $_countryCollectionFactory;
    protected ResourceModel $resourceModel;
    protected CustomerFactory $customerFactory;
    protected DirectoryHelper $directoryHelper;
    protected ?Customer $customer = null;
    protected Session $session;

    public function __construct(
        Context                   $context,
        BussinesCollectionFactory $collectionFactory,
        CollectionFactory         $_countryCollectionFactory,
        ResourceModel             $resourceModel,
        CustomerFactory           $customerFactory,
        DirectoryHelper           $directoryHelper,
        Session                   $session,
        array                     $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->_countryCollectionFactory = $_countryCollectionFactory;
        $this->session = $session;
        $this->resourceModel = $resourceModel;
        $this->customerFactory = $customerFactory;
        $this->directoryHelper = $directoryHelper;

    }


    /**
     * @return string url to send form
     */
    public function formAction(): string
    {
        return $this->_urlBuilder->getUrl('customers/register/submit');
    }

    /**
     * @return Country[]|Collection
     * Allowed Countries Getter.
     * @throws NoSuchEntityException
     */
    public function getAllowedCountries(): Collection
    {
        return $this->_countryCollectionFactory->create()->loadByStore($this->_storeManager->getStore()->getId());
    }

    public function getCustomer(): Customer
    {
        if ($this->customer) {
            return $this->customer;
        }
        $this->customer = $this->customerFactory->create();
        $this->resourceModel->load($this->customer, $this->session->getCustomerId());
        return $this->customer;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreCode(): string
    {
        return $this->_storeManager->getStore()->getCode();
    }

    public function display(): bool
    {
        $item = $this->collectionFactory
            ->create()
            ->addFieldToFilter('customers_id', array('eq' => $this->session->getCustomerId()))
            ->load()
            ->getFirstItem();
        return true;
    }

    /**
     * @throws LocalizedException
     */
    public function displayText(): bool
    {
        return $this->_storeManager->getWebsite()->getCode() != 'ec';
    }
}
