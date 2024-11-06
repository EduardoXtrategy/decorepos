<?php

namespace Uzer\OnDemand\Block;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModelCustomer;
use Magento\Framework\App\ObjectManager;
use Uzer\OnDemand\Helper\Data;

class OnDemand extends \Uzer\Samples\Block\Product\View
{


    protected ?Customer $customer = null;
    protected Data $helperData;
    protected $preSaleId;
    protected $ondemandId;

    public function __construct(
        \Magento\Catalog\Block\Product\Context              $context,
        \Magento\Framework\Url\EncoderInterface             $urlEncoder,
        \Magento\Framework\Json\EncoderInterface            $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils               $string,
        \Magento\Catalog\Helper\Product                     $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface           $localeFormat,
        \Magento\Customer\Model\Session                     $customerSession,
        ProductRepositoryInterface                          $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface   $priceCurrency,
        Configurable                                        $configurable,
        Data                                                $helperData,
        array                                               $data = []
    )
    {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $configurable,
            $data
        );
        $this->helperData = $helperData;
    }


    protected function _beforeToHtml()
    {
        $this->preSaleId = $this->helperData->getPreSaleAttribute($this->_storeManager->getStore()->getId());
        $this->ondemandId = $this->helperData->getOnDemandAttribute($this->_storeManager->getStore()->getId());
        return parent::_beforeToHtml();
    }

    public function displayForm(): bool
    {
        if (parent::displayForm()) {
            return !$this->isOnDemand();
        }
        return false;
    }


    public function isOnDemand(): bool
    {
        $bModel = $this->getProduct()->getBModel();
        if ($bModel == $this->ondemandId || $bModel == $this->preSaleId) {
            return true;
        }
        return false;
    }

    public function isLogged()
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomer(): Customer
    {
        if (!$this->customer) {
            $resourceModel = ObjectManager::getInstance()->create(ResourceModelCustomer::class);
            $this->customer = ObjectManager::getInstance()->create(CustomerFactory::class)->create();
            $resourceModel->load($this->customer, $this->customerSession->getCustomerId());
        }
        return $this->customer;
    }

    public function getText()
    {

        if ($this->getProduct()->getBModel() == $this->preSaleId) {
            return $this->helperData->getPreSaleText($this->_storeManager->getStore()->getId());
        } else if ($this->getProduct()->getBModel() == $this->ondemandId) {
            return $this->helperData->getOnDemandText($this->_storeManager->getStore()->getId());
        }
        return false;
    }
}
