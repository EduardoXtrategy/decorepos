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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class BaseForm extends Template
{

    protected array $_templates = [
        'us_en' => 'Uzer_Customer::base/us_template.phtml',
        'us_es' => 'Uzer_Customer::base/us_template.phtml',
        'eu_fr' => 'Uzer_Customer::base/eu_template.phtml',
        'eu_en' => 'Uzer_Customer::base/eu_template.phtml',
        'eu_es' => 'Uzer_Customer::base/eu_template.phtml',
        'eu_ger' => 'Uzer_Customer::base/eu_template.phtml',
        'eu_ned' => 'Uzer_Customer::base/eu_template.phtml',
        'lat_es' => 'Uzer_Customer::base/lat_template.phtml',
        'lat_en' => 'Uzer_Customer::base/lat_template.phtml',
        'ec_en' => 'Uzer_Customer::base/ec_template.phtml',
    ];


    protected CollectionFactory $_countryCollectionFactory;
    protected ResourceModel $resourceModel;
    protected CustomerFactory $customerFactory;
    protected DirectoryHelper $directoryHelper;
    protected ?Customer $customer = null;
    protected Session $session;

    public function __construct(
        Context           $context,
        CollectionFactory $_countryCollectionFactory,
        ResourceModel     $resourceModel,
        CustomerFactory   $customerFactory,
        DirectoryHelper   $directoryHelper,
        Session           $session,
        array             $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_countryCollectionFactory = $_countryCollectionFactory;
        $this->session = $session;
        $this->resourceModel = $resourceModel;
        $this->customerFactory = $customerFactory;
        $this->directoryHelper = $directoryHelper;
    }

    protected function _beforeToHtml()
    {
        return parent::_beforeToHtml();
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getTemplate(): string
    {
        $code = $this->_storeManager->getStore()->getCode();
        $template = 'Uzer_Customer::register/empty';
        if (isset($this->_templates[$code])) {
            $template = $this->_templates[$code];
        }
        $this->setTemplate($template);
        return $this->_template;
    }


    public function getDownloadLink(): string
    {
        return '';
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

}
