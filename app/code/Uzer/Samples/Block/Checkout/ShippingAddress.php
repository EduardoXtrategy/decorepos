<?php

namespace Uzer\Samples\Block\Checkout;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Block\Address\Renderer\RendererInterface;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\Address\Config;
use Magento\Customer\Model\Address\Mapper;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModelCustomer;
use Magento\Customer\Model\Session;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Controller\ResultFactory;
use Magento\Directory\Model\Country;
use Magento\Customer\Model\Session as customerSession;
use Magento\Framework\View\Element\Template\Context;
use Uzer\Samples\Block\Cart\BaseBlock;

class ShippingAddress extends BaseBlock
{

    protected Session $session;
    protected CustomerFactory $customerFactory;
    protected ?Customer $customer = null;
    protected ?Address $defaultShippingAddress = null;
    protected bool $queryAddress = false;
    protected Country $country;
    protected ResultFactory $resultFactory;
    protected DirectoryHelper $directoryHelper;
    protected customerSession $customerSession;
    protected CollectionFactory $_countryCollectionFactory;
    protected ResourceModelCustomer $resourceModelCustomer;
    protected Config $_addressConfig;
    protected Mapper $addressMapper;

    /**
     * @param Context $context
     * @param CurrencyFactory $currencyFactory
     * @param DirectoryHelper $directoryHelper
     * @param ResultFactory $resultFactory
     * @param customerSession $customerSession
     * @param Session $session
     * @param CollectionFactory $_countryCollectionFactory
     * @param CustomerFactory $customerFactory
     * @param ResourceModelCustomer $resourceModelCustomer
     * @param Config $_addressConfig
     * @param Mapper $addressMapper
     * @param array $data
     */
    public function __construct(
        Template\Context      $context,
        CurrencyFactory       $currencyFactory,
        DirectoryHelper       $directoryHelper,
        ResultFactory         $resultFactory,
        customerSession       $customerSession,
        Session               $session,
        CollectionFactory     $_countryCollectionFactory,
        CustomerFactory       $customerFactory,
        ResourceModelCustomer $resourceModelCustomer,
        Config                $_addressConfig,
        Mapper                $addressMapper,
        array                 $data = [])
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->directoryHelper = $directoryHelper;
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession;
        $this->session = $session;
        $this->_countryCollectionFactory = $_countryCollectionFactory;
        $this->resourceModelCustomer = $resourceModelCustomer;
        $this->customerFactory = $customerFactory;
        $this->_addressConfig = $_addressConfig;
        $this->addressMapper = $addressMapper;
    }

    public function getSessionCustomer(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getRedirectLogin()
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('customer/account/login');
        return $redirect;
    }


    /**
     * @return Address[]
     */
    public function getCustomerAddresses(): array
    {
        if (is_null($this->customer)) {
            $this->customer = $this->customerFactory->create();
            $this->resourceModelCustomer->load($this->customer, $this->customerSession->getCustomerId());
        }
        return $this->customer->getAddresses();
    }

    public function isDefaultShipping(Address $address): bool
    {
        $this->getCustomerAddresses();
        if (!$this->queryAddress) {
            $defaultShipping = $this->customer->getDefaultShippingAddress();
            if ($defaultShipping) {
                $this->defaultShippingAddress = $defaultShipping;
            }
            $this->queryAddress = true;
        }
        return !is_null($this->defaultShippingAddress) && $address->getId() == $this->defaultShippingAddress->getId();
    }

    public function getDefaultSelected()
    {
        $request = ObjectManager::getInstance()->create(RequestInterface::class);
        return $request->getParam('selected');
    }

    /**
     * @return Country[]|Collection
     * Allowed Countries Getter.
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAllowedCountries(): Collection
    {
        return $this->_countryCollectionFactory->create()->loadByStore($this->_storeManager->getStore()->getId());
    }

    /**
     * Retrieve list of top destinations countries
     *
     * @return array
     */
    protected function getTopDestinations(): array
    {
        $destinations = (string)$this->_scopeConfig->getValue(
            'general/country/destinations',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return !empty($destinations) ? explode(',', $destinations) : [];
    }

    /**
     * @throws NoSuchEntityException
     */
    public function showState(): bool
    {
        $countries = $this->getAllowedCountries();
        foreach ($countries as $country) {
            if ($this->directoryHelper->isRegionRequired($country->getCountryId())) {
                return true;
            }
        }
        return trim($this->directoryHelper->isShowNonRequiredState());
    }

    public function getFormattedAddress(AddressInterface $address): string
    {
        /** @var RendererInterface $renderer */
        $renderer = $this->_addressConfig->getFormatByCode('html')->getRenderer();
        return $renderer->renderArray($this->addressMapper->toFlatArray($address));
    }
}
