<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Mostviewed
 */


namespace Amasty\Mostviewed\Model;

use Amasty\Mostviewed\Model\OptionSource\ReplaceType;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Amasty\Mostviewed\Model\ResourceModel\Product\CollectionFactory;
use Amasty\Mostviewed\Model\ResourceModel\Product\Collection;
use Amasty\Mostviewed\Model\OptionSource\SourceType;
use Magento\Framework\DB\Select;
use Magento\Catalog\Model\Product\Visibility;
use Amasty\Mostviewed\Model\OptionSource\Sortby;

class ProductProvider
{
    const MAX_COLLECTION_SIZE = 1000;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ResourceModel\RuleIndex
     */
    private $indexResource;

    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var Repository\GroupRepository
     */
    private $groupRepository;

    /**
     * @var Visibility
     */
    private $catalogProductVisibility;

    /**
     * @var \Magento\Catalog\Model\Config
     */
    private $catalogConfig;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    private $stockHelper;

    /**
     * @var \Amasty\Mostviewed\Helper\Config
     */
    private $config;

    /**
     * @var ResourceModel\Product\LoadViews
     */
    private $loadViews;

    /**
     * @var ResourceModel\Product\LoadBoughtTogether
     */
    private $loadBoughtTogether;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Amasty\Mostviewed\Model\ResourceModel\RuleIndex $indexResource,
        CollectionFactory $productCollectionFactory,
        \Amasty\Mostviewed\Model\Repository\GroupRepository $groupRepository,
        Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\CatalogInventory\Helper\Stock $stockHelper,
        \Amasty\Mostviewed\Helper\Config $config,
        \Amasty\Mostviewed\Model\ResourceModel\Product\LoadViews $loadViews,
        \Amasty\Mostviewed\Model\ResourceModel\Product\LoadBoughtTogether $loadBoughtTogether
    ) {
        $this->storeManager = $storeManager;
        $this->indexResource = $indexResource;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->groupRepository = $groupRepository;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->catalogConfig = $catalogConfig;
        $this->stockHelper = $stockHelper;
        $this->config = $config;
        $this->loadViews = $loadViews;
        $this->loadBoughtTogether = $loadBoughtTogether;
    }

    /**
     * @param Group $group
     * @param $entity
     *
     * @return Collection|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAppliedProducts(Group $group, $entity)
    {
        /** @var Collection $products */
        $products = $this->getProductCollection($group);

        if ($entity instanceof Product) {
            switch ($group->getSourceType()) {
                case SourceType::SOURCE_BOUGHT:
                    $products = $this->applyBoughtTogether($products, $entity, $group);
                    break;
                case SourceType::SOURCE_VIEWED:
                    $products = $this->applyViewedTogether($products, $entity, $group);
                    break;
            }

            if ($products && $group->getSameAs()) {
                $group->applySameAsConditions($products, $entity);
            }
        }

        if ($entity instanceof Category && $group->getIsCurrentCategoryOnly()) {
            $products->addCategoryFilter($entity);
        }

        return $products;
    }

    /**
     * @param Group $group
     * @return \Amasty\Mostviewed\Model\ResourceModel\Product\Collection|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getProductCollection(Group $group)
    {
        $collection = $this->productCollectionFactory->create()
            ->setStoreId($this->storeManager->getStore()->getId());

        $conditions = $group->getConditions()->getConditions();
        if ($conditions) {
            $this->indexResource->applyProductsFilterToCollection($collection, $group->getGroupId());
        }

        return $collection;
    }

    /**
     * @param string $type
     * @param Product $product
     * @param $collection
     * @param array $excludedProducts
     * @param $block
     *
     * @return Collection
     */
    public function modifyCollection(
        $type,
        Product $product,
        $collection,
        $excludedProducts,
        $block
    ) {
        $group = $this->groupRepository->getGroupByIdAndPosition($product->getId(), $type);
        if ($group) {
            $limit = $group->getMaxProducts() ? : self::MAX_COLLECTION_SIZE;

            $shouldAdd = $group->getReplaceType() == ReplaceType::ADD;
            if ($shouldAdd) {
                if (is_object($collection)) {
                    $appendIds = $collection->getAllIds();
                } else {
                    $appendIds = array_map(function ($product) {
                        return $product->getId();
                    }, $collection);
                }

                $excludedProducts = array_merge($excludedProducts, $appendIds);
                $limit -= count($appendIds);
            }

            if ($limit > 0) {
                $appliedCollection = $this->getAppliedProducts($group, $product);
                if ($appliedCollection) {
                    $appliedCollection->setPageSize($limit);

                    if (!empty($excludedProducts)) {
                        $appliedCollection->addIdFilter($excludedProducts, true);
                    }

                    $this->prepareCollection($group, $appliedCollection, (int)$product->getId());
                    $block->setMostviewedProducts(array_keys($appliedCollection->getItems()));

                    $finalItems = [];
                    if ($shouldAdd) {
                        foreach ($collection as $item) {
                            $finalItems[] = $item;
                        }
                    }
                    foreach ($appliedCollection as $item) {
                        $finalItems[] = $item;
                    }

                    $appliedCollection->setItems($finalItems);
                    $appliedCollection->updateTotalRecords();

                    if (is_array($collection)) {
                        $collection = $appliedCollection->getItems();
                    } else {
                        $collection = $appliedCollection;
                    }
                    $block->setGroupId($group->getGroupId());
                }
            }
        }

        return $collection;
    }

    public function prepareCollection(Group $group, Collection $collection, ?int $productId = null): void
    {
        $collection->addAttributeToSelect(
            'required_options'
        )->addStoreFilter();

        $collection
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite();

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        if (!$group->getShowOutOfStock()) {
            $this->stockHelper->addInStockFilterToCollection($collection);
        }

        $this->applySorting($group->getSorting(), $collection);

        if ($productId) {
            $collection->addIdFilter($productId, true);
        }

        foreach ($collection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
    }

    /**
     * @param $sorting
     * @param Collection $collection
     */
    private function applySorting($sorting, Collection $collection)
    {
        $dir = Select::SQL_ASC;
        switch ($sorting) {
            case Sortby::NAME:
                $sortAttr = 'name';
                break;
            case Sortby::PRICE_ASC:
                $sortAttr = 'price';
                break;
            case Sortby::PRICE_DESC:
                $sortAttr = 'price';
                $dir = Select::SQL_DESC;
                break;
            case Sortby::NEWEST:
                $sortAttr = 'created_at';
                $dir = Select::SQL_DESC;
                break;
            default:
                $sortAttr = null;
        }
        if ($sortAttr === null) {
            $collection->getSelect()->order('RAND()');
        } else {
            $collection->setOrder($sortAttr, $dir);
        }
    }

    /**
     * @param Collection $collection
     * @param Product $product
     * @param Group $group
     *
     * @return Collection|bool
     */
    private function applyViewedTogether(Collection $collection, Product $product, Group $group)
    {
        $data = $this->loadViews->execute(
            (int) $product->getId(),
            [(int) $this->storeManager->getStore()->getId()],
            (int) $this->config->getGatheredPeriod()
        );

        $views = [];
        $products = [];
        foreach ($data as $key => $row) {
            $views[$key] = $row['cnt'];
            $products[$key] = $row['id'];
        }

        array_multisort($views, SORT_DESC, $products);
        if (!empty($products)) {
            $collection->addIdFilter(array_unique($products));
            $collection->getSelect()->order(
                new \Zend_Db_Expr('FIELD(e.entity_id, ' . implode(',', $products) . ')')
            );
        } else {
            $collection = false;
        }

        return $collection;
    }

    /**

     * @param Product $product
     *
     * @return array
     */
    private function getProductIdsByType(Product $product)
    {
        $productIds = [];

        $typeInstance = $product->getTypeInstance();
        switch ($product->getTypeId()) {
            case 'grouped':
                $productIds = $typeInstance->getAssociatedProductIds($product);
                break;
            case 'configurable':
                $productIds = $typeInstance->getUsedProductIds($product);
                break;
            case 'bundle':
                $optionsIds = $typeInstance->getOptionsIds($product);
                $selections = $typeInstance->getSelectionsCollection($optionsIds, $product);
                foreach ($selections as $selection) {
                    $productIds[] = $selection->getProductId();
                }
                break;
            default:
                $productIds[] = $product->getId();
        }

        return $productIds;
    }

    /**
     * @param Collection $collection
     * @param Product $product
     * @param Group $group
     *
     * @return Collection
     */
    private function applyBoughtTogether(Collection $collection, Product $product, Group $group)
    {
        $data = $this->loadBoughtTogether->execute(
            $this->getProductIdsByType($product),
            [(int) $this->storeManager->getStore()->getId()],
            (int) $this->config->getGatheredPeriod(),
            [$this->config->getOrderStatus()]
        );

        if (empty($data)) {
            $collection = false;
        } else {
            $views = [];
            $products = [];
            foreach ($data as $key => $row) {
                $views[$key] = $row['cnt'];
                $products[$key] = $row['id'];
            }

            array_multisort($views, SORT_DESC, $products);
            if (!empty($products)) {
                $collection->addIdFilter(array_unique($products));
                $collection->getSelect()->order(
                    new \Zend_Db_Expr('FIELD(e.entity_id, ' . implode(',', $products) . ')')
                );
            }
        }

        return $collection;
    }
}
