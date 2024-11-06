<?php

namespace Uzer\Theme\Block;

class MostViewedProducts extends \Sparsh\MostViewedProducts\Block\MostViewedProducts
{
    /**
     * @var ImageBuilder
     * @since 102.0.0
     */
    protected $imageBuilder;

    /**
     * Initialize Objects
     *
     * @param \Magento\Framework\View\Element\Template\Context               $context           Initialize Context list
     * @param \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $CollectionFactory Initialize Collection factory list
     * @param \Magento\Catalog\Block\Product\ListProduct                     $listProduct       Initialize List Product
     * @param \Magento\Store\Model\StoreManagerInterface                     $storeManager      Initialize Store manager
     * @param \Magento\Review\Model\ReviewFactory                            $reviewFactory     Initialize Review Factory
     * @param array                                                          $data              Initialize data array
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $CollectionFactory,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        array $data = []
    ) {

        $this->imageBuilder = $imageBuilder;
        parent::__construct($context,
            $CollectionFactory,
            $listProduct,
            $storeManager,
            $reviewFactory,
            $data);
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
}
