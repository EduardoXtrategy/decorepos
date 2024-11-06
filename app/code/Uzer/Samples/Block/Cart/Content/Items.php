<?php

namespace Uzer\Samples\Block\Cart\Content;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\View\Element\Template;
use Uzer\Samples\Block\Cart\BaseBlock;
use Uzer\Samples\Model\SampleCartItem;
use Uzer\Samples\Model\Session;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModel;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Items extends BaseBlock
{

    private Session $session;
    /**
     * @var SampleCartItem[]
     *
     */
    private array $items = [];
    private bool $loaded = false;
    protected Image $imageHelper;
    protected ProductFactory $productFactory;
    protected ResourceModel $resourceModel;
    protected $productRepository;

    /**
     * @param Template\Context $context
     * @param Session $session
     * @param Image $imageHelper
     * @param ProductFactory $productFactory
     * @param ResourceModel $resourceModel
     * @param array $data
     */
    public function __construct(
        Template\Context           $context,
        CurrencyFactory            $currencyFactory,
        Session                    $session,
        Image                      $imageHelper,
        ProductFactory             $productFactory,
        ResourceModel              $resourceModel,
        ProductRepositoryInterface $productRepository,
        array                      $data = []
    )
    {
        parent::__construct($context, $currencyFactory, $data);
        $this->session = $session;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->resourceModel = $resourceModel;
        $this->productRepository = $productRepository;
    }

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function hasItems(): bool
    {
        if (!$this->session->hasSamplesCart()) {
            return false;
        }
        return count($this->getItems()) > 0;
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

    public function getProduct($id)
    {
        return $this->productFactory->create()->load($id);
    }

}
