<?php

namespace Uzer\Samples\Block\Adminhtml\Samples;

use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModel;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Quote\Api\CartRepositoryInterface;
use Uzer\Samples\Model\ResourceModel\SampleOrderFactory as ResourceModelFactory;
use Uzer\Samples\Model\SampleOrderFactory;
use Uzer\Samples\Model\ResourceModel\SampleCartItem\CollectionFactory;


class Products extends \Magento\Backend\Block\Template
{


    protected ?string $currencyCode = null;
    protected CurrencyFactory $currencyFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected SampleOrderFactory $sampleOrderFactory;
    protected CartRepositoryInterface $cartRepository;
    protected RequestInterface $request;
    protected CollectionFactory $sampleCartItemCollectionFactory;
    protected ProductRepositoryInterface $productRepository;
    private array $items = [];
    private bool $loaded = false;
    protected Image $imageHelper;
    protected ProductFactory $productFactory;
    protected ResourceModel $resourceModel;

    public function __construct(
        Context                    $context,
        RequestInterface           $request,
        ResourceModelFactory       $resourceModelFactory,
        SampleOrderFactory         $sampleOrderFactory,
        CartRepositoryInterface    $cartRepository,
        CollectionFactory          $sampleCartItemCollectionFactory,
        ProductRepositoryInterface $productRepository,
        CurrencyFactory            $currencyFactory,
        Image                      $imageHelper,
        ProductFactory             $productFactory,
        ResourceModel              $resourceModel,
        array                      $data = [],
        ?JsonHelper                $jsonHelper = null,
        ?DirectoryHelper           $directoryHelper = null
    )
    {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
        $this->request = $request;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->sampleOrderFactory = $sampleOrderFactory;
        $this->cartRepository = $cartRepository;
        $this->sampleCartItemCollectionFactory = $sampleCartItemCollectionFactory;
        $this->productRepository = $productRepository;
        $this->currencyFactory = $currencyFactory;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->resourceModel = $resourceModel;
    }


    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'Uzer_Samples::samples/products.phtml';


    /**
     * @return []
     */
    public function getItems(): array
    {
        if (!$this->loaded) {
            $this->loaded = true;
            $sampleOrder = $this->sampleOrderFactory->create();
            $this->resourceModelFactory->create()->load($sampleOrder, $this->request->getParam('entity_id'));
            $this->items = $sampleOrder->getSampleCart()->getItems();
        }
        return $this->items;
    }

    public function getProductImage($id): string
    {
        $product = $this->productFactory->create();
        $this->resourceModel->create()->load($product, $id);
        return $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    }

    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencySymbol(): ?string
    {
        if (is_null($this->currencyCode)) {
            $currencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();
            $this->currencyCode = $this->currencyFactory->create()->load($currencyCode)->getCurrencySymbol();
        }
        return $this->currencyCode;
    }

    public function getProduct($id): Product
    {
        $product = $this->productFactory->create();
        $this->resourceModel->create()->load($product, $id);
        return $product;
    }
}
