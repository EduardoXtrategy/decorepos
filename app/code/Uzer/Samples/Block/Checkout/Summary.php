<?php

namespace Uzer\Samples\Block\Checkout;

use Magento\Catalog\Model\Product;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\View\Element\Template;
use Uzer\Samples\Block\Cart\BaseBlock;
use Uzer\Samples\Model\SampleCartItem;
use Uzer\Samples\Model\Session;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModel;
use Magento\Customer\Model\Session as customerSession;

class Summary extends BaseBlock
{
    private Session $session;
    /**
     * @var SampleCartItem[]
     *
     */
    private array $items = [];
    private bool $loaded = false;
    private customerSession $customerSession;

    public function __construct(
        Template\Context $context,
        CurrencyFactory  $currencyFactory,
        Image            $imageHelper,
        ProductFactory   $productFactory,
        ResourceModel    $resourceModel,
        customerSession  $customerSession,
        Session          $session, array $data = [])
    {
        $this->session = $session;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->resourceModel = $resourceModel;
        $this->customerSession = $customerSession;
        parent::__construct($context, $currencyFactory, $data);
    }

    public function getSessionCustomer()
    {

        return $this->customerSession->isLoggedIn();
    }


    /**
     * @return SampleCartItem[]
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function getItems(): array
    {
        if (!$this->loaded)
            $this->items = $this->session->getSamplesCart()->getItems();
        return $this->items;
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
