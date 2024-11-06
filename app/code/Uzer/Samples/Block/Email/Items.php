<?php

namespace Uzer\Samples\Block\Email;

use Magento\Catalog\Model\Product;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModel;
use Uzer\Samples\Block\Cart\BaseBlock;
use Uzer\Samples\Model\ResourceModel\SampleOrderFactory as ResourceModelFactory;
use Uzer\Samples\Model\ResourceModel\SamplesCartFactory as ResourceModelSampleCart;
use Uzer\Samples\Model\SampleOrder;
use Uzer\Samples\Model\SampleOrderFactory;
use Uzer\Samples\Model\SamplesCart;
use Uzer\Samples\Model\SamplesCartFactory;


class Items extends BaseBlock
{

    private ResourceModelFactory $resourceModelFactory;
    private SampleOrderFactory $sampleOrderFactory;
    private ResourceModelSampleCart $samplesCartResourceModel;
    private SamplesCartFactory $samplesCartFactory;
    private SamplesCart $samplesCart;
    private SampleOrder $sampleOrder;
    protected ProductFactory $productFactory;
    protected Image $imageHelper;
    protected ResourceModel $resourceModel;


    public function __construct(
        Template\Context        $context,
        CurrencyFactory         $currencyFactory,
        ResourceModelFactory    $resourceModelFactory,
        ResourceModelSampleCart $samplesCartResourceMode,
        SampleOrderFactory      $sampleOrderFactory,
        SamplesCartFactory      $samplesCartFactory,
        ProductFactory          $productFactory,
        Image                   $imageHelper,
        ResourceModel           $resourceModel,
        array                   $data = []
    )
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->resourceModelFactory = $resourceModelFactory;
        $this->sampleOrderFactory = $sampleOrderFactory;
        $this->samplesCartResourceModel = $samplesCartResourceMode;
        $this->samplesCartFactory = $samplesCartFactory;
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
        $this->resourceModel = $resourceModel;
    }


    protected function _beforeToHtml(): Items
    {
        $this->sampleOrder = $this->sampleOrderFactory->create();
        $this->resourceModelFactory->create()->load($this->sampleOrder, $this->getData('samples_order_id'));
        $this->samplesCart = $this->samplesCartFactory->create();
        $this->samplesCartResourceModel->create()->load($this->samplesCart, $this->sampleOrder->getSampleQuoteId());
        return parent::_beforeToHtml();
    }

    public function getItems(): array
    {
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

}
