<?php


namespace Uzer\Search\Block\CatalogSearch;


use Exception;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Uzer\Search\Model\ProductBannerModel;
use Uzer\Search\Model\ProductBannerModelFactory;
use Uzer\Search\Model\ResourceModel\ProductBannerModel\ProductBannerCollectionFactory;
use Uzer\Search\Model\ResourceModel\ProductBannerResourceFactory;

class Search extends \Magento\Framework\View\Element\Template
{

    protected ProductBannerResourceFactory $productBannerResourceFactory;
    protected ProductBannerModelFactory $productBannerModelFactory;
    protected ProductBannerCollectionFactory $collectionFactory;
    protected FilterProvider $_filterProvider;

    public function __construct(
        Template\Context               $context,
        ProductBannerResourceFactory   $productBannerResourceFactory,
        ProductBannerModelFactory      $productBannerModelFactory,
        ProductBannerCollectionFactory $collectionFactory,
        FilterProvider                 $_filterProvider,
        array                          $data = [])
    {
        parent::__construct($context, $data);
        $this->productBannerResourceFactory = $productBannerResourceFactory;
        $this->productBannerModelFactory = $productBannerModelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->_filterProvider = $_filterProvider;
    }


    /**
     * @return string|null
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function getContentFilter(): ?string
    {
        $productType = $this->getRequest()->getParam('product_type_decowraps', null);
        if (!$productType) {
            return $this->getItem(0);
        }
        $productTypes = explode(',', trim($productType));
        if (count($productTypes) <> 1) {
            return $this->getItem(0);
        }
        if (count($productTypes) == 1) {
            return $this->getItem($productTypes[0]);
        }
        return null;
    }

    /**
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function getItem($attributeId): ?string
    {
        /** @var ProductBannerModel $item */
        $item = $this->collectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter('attribute_id', array('eq' => $attributeId))
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->load()
            ->getFirstItem();
        if (!$item->isEmpty()) {
            return $this->_filterProvider->getBlockFilter()->filter($item->getContent());
        }
        return null;
    }

}
