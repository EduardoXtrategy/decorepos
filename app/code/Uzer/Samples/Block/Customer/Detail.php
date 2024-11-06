<?php

namespace Uzer\Samples\Block\Customer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModel;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Block\Address\Renderer\RendererInterface;
use Magento\Customer\Model\Address\Config;
use Magento\Customer\Model\Address\Mapper;
use Magento\Directory\Model\Country;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Samples\Model\ResourceModel\SamplesCartFactory as ResourceModelSampleCart;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;
use Uzer\Samples\Block\Cart\BaseBlock;
use Uzer\Samples\Model\ResourceModel\SampleOrderFactory as ResourceModelFactory;
use Uzer\Samples\Model\SampleOrder;
use Uzer\Samples\Model\SampleOrderFactory;
use Uzer\Samples\Model\SamplesCart;
use Uzer\Samples\Model\SamplesCartFactory;

class Detail extends BaseBlock
{

    protected Image $imageHelper;
    protected ResourceModel $resourceModel;
    protected ProductFactory $productFactory;
    protected RequestInterface $request;
    protected SampleOrderFactory $sampleOrderFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected ResourceModelSampleCart $samplesCartResourceModel;
    protected SamplesCartFactory $samplesCartFactory;
    protected SampleOrder $sampleOrder;
    protected SamplesCart $samplesCart;
    protected Config $_addressConfig;
    protected Mapper $addressMapper;


    public function __construct(
        Template\Context        $context,
        CurrencyFactory         $currencyFactory,
        ResourceModelFactory    $resourceModelFactory,
        SampleOrderFactory      $sampleOrderFactory,
        ProductFactory          $productFactory,
        SamplesCartFactory      $samplesCartFactory,
        ResourceModelSampleCart $samplesCartResourceModel,
        Image                   $imageHelper,
        ResourceModel           $resourceModel,
        RequestInterface        $request,
        Config                  $_addressConfig,
        Mapper                  $addressMapper,
        array                   $data = []
    )
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->resourceModelFactory = $resourceModelFactory;
        $this->sampleOrderFactory = $sampleOrderFactory;
        $this->samplesCartFactory = $samplesCartFactory;
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
        $this->resourceModel = $resourceModel;
        $this->samplesCartResourceModel = $samplesCartResourceModel;
        $this->request = $request;
        $this->_addressConfig = $_addressConfig;
        $this->addressMapper = $addressMapper;
    }

    public function getSampleOrder()
    {
        return $this->request->getParam('order');
    }

    public function getSampleOrderDetail()
    {
        $this->sampleOrder = $this->sampleOrderFactory->create();
        $this->resourceModelFactory->create()->load($this->sampleOrder, $this->request->getParam('order'));
        return $this->sampleOrder;

    }

    public function getCartItems(): array
    {
        $this->samplesCart = $this->samplesCartFactory->create();
        $this->samplesCartResourceModel->create()->load($this->samplesCart, $this->getSampleOrderDetail()->getSampleQuoteId());
        return $this->samplesCart->getItems();
    }


    public function getProductImage($id): string
    {
        $product = $this->productFactory->create();
        $this->resourceModel->create()->load($product, $id);
        return $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    }

    public function getProduct($id): Product
    {
        $product = $this->productFactory->create();
        $this->resourceModel->create()->load($product, $id);
        return $product;
    }

    /**
     */
    public function getProductBySku($sku): ?ProductInterface
    {
        try {
            $product = ObjectManager::getInstance()->create(ProductRepositoryInterface::class)->get($sku);
            $this->resourceModel->create()->load($product, $product->getId());
            return $product;
        } catch (\Exception $ex) {

        }
        return null;
    }

    public function getStoreTax(): Phrase
    {
        $code = $this->_storeManager->getStore()->getCode();
        $codes = array('eu_en', 'eu_fr', 'eu_es', 'eu_ger', 'eu_ned', 'en_eu', 'ned_eu', 'ger_eu', 'es_eu', 'fr_eu');
        if (in_array($code, $codes)) {
            return __('VAT');
        }
        return __('TAX');
    }

    /**
     * @return AddressInterface|null
     */
    public function getShippingAddress(): ?AddressInterface
    {
        $id = $this->getSampleOrderDetail()->getCustomerAddressId();
        try {
            $address = ObjectManager::getInstance()->create(AddressRepositoryInterface::class)->getById($id);
            if ($address) {
                return $address;
            }
        } catch (LocalizedException $e) {
        }
        return null;
    }

    public function getCountryNameByCode(string $countryCode): ?Country
    {
        $country = ObjectManager::getInstance()->create(Country::class)->loadByCode($countryCode);
        if ($country) {
            return $country;
        }
        return null;
    }

    public function getFormattedAddress(AddressInterface $address): string
    {
        /** @var RendererInterface $renderer */
        $renderer = $this->_addressConfig->getFormatByCode('html')->getRenderer();
        return $renderer->renderArray($this->addressMapper->toFlatArray($address));
    }

}
