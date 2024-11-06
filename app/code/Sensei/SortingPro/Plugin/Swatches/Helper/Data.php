<?php

namespace Sensei\SortingPro\Plugin\Swatches\Helper;

class Data
{
    /**
     * @var \Sensei\SortingPro\Helper\Data
     */
    protected $sp2Helper;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    /**
     * @var array
     */
    protected $activeFilters;

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * Product metadata pool
     *
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    protected $metadataPool;

    /**
     * @var array
     */
    protected $queryFlags;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        \Sensei\SortingPro\Helper\Data $sp2Helper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->sp2Helper = $sp2Helper;
        $this->layerResolver = $layerResolver;
        $layer = $this->layerResolver->get();
        $this->activeFilters = $layer->getState()->getFilters();
        $this->productCollectionFactory = $productCollectionFactory;
        $this->eavConfig = $eavConfig;
        $this->resource = $resource;
        $this->productRepository = $productRepository;
    }

    public function afterLoadVariationByFallback($subject, $result, \Magento\Catalog\Api\Data\ProductInterface $parentProduct, array $attributes)
    {
        if (!$subject->isProductHasSwatch($parentProduct)) {
            return false;
        }

        $productCollection = $this->productCollectionFactory->create();

        $productLinkedFiled = $this->getMetadataPool()
            ->getMetadata(\Magento\Catalog\Api\Data\ProductInterface::class)
            ->getLinkField();
        $parentId = $parentProduct->getData($productLinkedFiled);

        $this->addFilterByParent($productCollection, $parentId);

        $configurableAttributes = $subject->getAttributesFromConfigurable($parentProduct);

        $resultAttributesToFilter = [];
        foreach ($configurableAttributes as $attribute) {
            $attributeCode = $attribute->getData('attribute_code');
            if (array_key_exists($attributeCode, $attributes)) {
                $resultAttributesToFilter[$attributeCode] = $attributes[$attributeCode];
            }
        }

        /* DecoWraps Customization - Forcing Image Preview for some attributes non-configurable or non-super attributes - Start */
        $forcedAttributes = $this->sp2Helper->getScopeValue("image_preview/forced_attributes", $this->sp2Helper->getCurrentStoreId());
        if (!trim($forcedAttributes)) {
            return $result;
        }
        $productFlatFlag = $this->sp2Helper->isCatalogProductFlatEnabled();
        $forcedAttributes = explode(",", $forcedAttributes);
        $forcedAttributes = array_map('trim', $forcedAttributes);
        if (count($forcedAttributes)) {
            foreach($this->activeFilters as $f) {
                $activeFilterCode = $f->getFilter()->getRequestVar();
                $activeFilterValue = $f->getValueString();
                foreach ($attributes as $ak => $av) {
                    if (!in_array($ak, $forcedAttributes)) {
                        continue;
                    }
                    if (!isset($attributes[$ak])) {
                        continue;
                    }
                    $attributeObj = $this->eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $ak);
                    if (!$attributeObj->getId()) {
                        continue;
                    }
                    if ($attributeObj->getFrontendInput() != "multiselect") {
                        $resultAttributesToFilter[$ak] = $attributes[$ak];
                        continue;
                    }
                    $existsColum = false;
                    $queryStr = $productCollection->getSelect()->__toString();
                    if (strpos($queryStr, "catalog_product_flat_") !== false) {
                        $existsColum = true;
                    }
                    $avArr = explode(",", $av);
                    if ($existsColum) {
                        foreach ($avArr as $avK => $avV) {
                            $attrTable0Alias = $ak . "_table_" . $avV;
                            if (strpos($queryStr, $attrTable0Alias) !== false) {
                                continue;
                            }
                            $whereStr[] = "(e.{$ak} LIKE '{$avV}' OR e.{$ak} LIKE '%,{$avV},%'
                        OR e.{$ak} LIKE '{$avV},%' OR e.{$ak} LIKE '%,{$avV}')";
                        }
                        if (count($whereStr)) {
                            $productCollection->getSelect()->where(implode(" OR ", $whereStr));
                        }
                    } else {
                        $whereStr = [];
                        foreach ($avArr as $avK => $avV) {
                            $attrTable0Alias = $ak . "_table_" . $avV;
                            if (strpos($queryStr, $attrTable0Alias) !== false) {
                                continue;
                            }
                            $attrTable1Alias = $ak . "_table_s" . $this->sp2Helper->getCurrentStoreId() . "_" . $avV;
                            $connection = $this->resource->getConnection();
                            $pvName = $connection->getTableName('catalog_product_entity_varchar');
                            $productCollection
                                ->getSelect()->joinLeft(
                                    [$attrTable0Alias => $pvName],
                                    "e.entity_id = {$attrTable0Alias}.entity_id AND {$attrTable0Alias}.store_id = 0
                                        AND {$attrTable0Alias}.attribute_id = {$attributeObj->getId()}
                                        AND ({$attrTable0Alias}.value LIKE '{$avV}' OR {$attrTable0Alias}.value LIKE '%,{$avV},%'
                                            OR {$attrTable0Alias}.value LIKE '{$avV},%' OR {$attrTable0Alias}.value LIKE '%,{$avV}')",
                                    []
                                )->joinLeft(
                                    [$attrTable1Alias => $pvName],
                                    "e.entity_id = {$attrTable1Alias}.entity_id AND {$attrTable1Alias}.store_id = {$this->sp2Helper->getCurrentStoreId()}
                                        AND {$attrTable1Alias}.attribute_id = {$attributeObj->getId()}
                                        AND ({$attrTable0Alias}.value LIKE '{$avV}' OR {$attrTable0Alias}.value LIKE '%,{$avV},%'
                                            OR {$attrTable0Alias}.value LIKE '{$avV},%' OR {$attrTable0Alias}.value LIKE '%,{$avV}')",
                                    []
                                );
                            $whereStr[] = "{$attrTable0Alias}.value IS NOT NULL OR {$attrTable1Alias}.value IS NOT NULL";
                        }
                        if (count($whereStr)) {
                            $productCollection->getSelect()->where(implode(" OR ", $whereStr));
                        }
                    }
                }
            }
        }
        /* DecoWraps Customization - Forcing Image Preview for some attributes non-configurable or non-super attributes - End */

        $this->addFilterByAttributes($productCollection, $resultAttributesToFilter);

        $variationProduct = $productCollection->getFirstItem();
        if ($variationProduct && $variationProduct->getId()) {
            return $this->productRepository->getById($variationProduct->getId());
        }

        return $result;
    }

    /**
     * Get product metadata pool.
     *
     * @return \Magento\Framework\EntityManager\MetadataPool
     * @deprecared
     */
    protected function getMetadataPool()
    {
        if (!$this->metadataPool) {
            $this->metadataPool = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\EntityManager\MetadataPool::class);
        }
        return $this->metadataPool;
    }

    /**
     * Add filter by attribute
     *
     * @param ProductCollection $productCollection
     * @param array $attributes
     * @return void
     */
    protected function addFilterByAttributes($productCollection, array $attributes)
    {
        foreach ($attributes as $code => $option) {
            $productCollection->addAttributeToFilter($code, ['eq' => $option]);
        }
    }

    /**
     * Add filter by parent
     *
     * @param ProductCollection $productCollection
     * @param integer $parentId
     * @return void
     */
    protected function addFilterByParent($productCollection, $parentId)
    {
        $tableProductRelation = $productCollection->getTable('catalog_product_relation');
        $productCollection
            ->getSelect()
            ->join(
                ['pr' => $tableProductRelation],
                'e.entity_id = pr.child_id'
            )
            ->where('pr.parent_id = ?', $parentId);
    }
}
