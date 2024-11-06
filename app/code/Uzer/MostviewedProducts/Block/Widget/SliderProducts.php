<?php

namespace Uzer\MostviewedProducts\Block\Widget;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\View\Element\Template;
use Magento\Reports\Model\ResourceModel\Product\CollectionFactory;
use Magento\Widget\Block\BlockInterface;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use \Magento\Catalog\Model\ResourceModel\Product\Collection;

class SliderProducts extends Template implements BlockInterface
{

    private static int $counter = 0;
    protected $_template = "Uzer_MostviewedProducts::widget/products.phtml";
    private CollectionFactory $productsFactory;
    private Status $productStatus;
    private Visibility $productVisibility;
    /**
     * @var ImageBuilder
     * @since 102.0.0
     */
    protected ImageBuilder $imageBuilder;
    protected ?Collection $products = null;
    private ProductCollectionFactory $productCollectionFactory;

    /**
     * @param Template\Context $context
     * @param CollectionFactory $productsFactory
     * @param Status $productStatus
     * @param Visibility $productVisibility
     * @param ImageBuilder $imageBuilder
     * @param ProductCollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context         $context,
        CollectionFactory        $productsFactory,
        Status                   $productStatus,
        Visibility               $productVisibility,
        ImageBuilder             $imageBuilder,
        ProductCollectionFactory $productCollectionFactory,
        array                    $data = [])
    {
        parent::__construct($context, $data);
        $this->productsFactory = $productsFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->imageBuilder = $imageBuilder;
        $this->productCollectionFactory = $productCollectionFactory;
    }


    /**
     * @return Product[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProducts(): array
    {
        if ($this->getData('widget_type') == 'featured') {
            return $this->getFeatured();
        } elseif ($this->getData('widget_type') == 'most_viewed') {
            return $this->getMostViewed();
        }
        return array();
    }

    protected function getMostViewed(): array
    {
        if (is_null($this->products)) {
            self::$counter++;
            $currentStoreId = $this->_storeManager->getStore()->getId();
            $this->products = $this->productsFactory->create()
                ->addAttributeToSelect('*')
                ->addViewsCount()
                ->addStoreFilter($currentStoreId)
                ->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
                ->setVisibility($this->productVisibility->getVisibleInSiteIds())
                ->setPageSize($this->getData('max_products'))
                ->load();
        }
        return $this->products->getItems();
    }

    public function getFeatured(): array
    {
        if (is_null($this->products)) {
            self::$counter++;
            $currentStoreId = $this->_storeManager->getStore()->getId();
            $this->products = $this->productCollectionFactory->create()
                ->addStoreFilter($currentStoreId)
                ->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
                ->setVisibility($this->productVisibility->getVisibleInSiteIds())
                ->setPageSize($this->getData('max_products'))
                ->addAttributeToFilter('is_featured', '1')
                ->load();
        }
        return $this->products->getItems();
    }

    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->create($product, $imageId, $attributes);
    }

    /**
     * @return int
     */
    public static function getCounter(): int
    {
        return self::$counter;
    }


}
