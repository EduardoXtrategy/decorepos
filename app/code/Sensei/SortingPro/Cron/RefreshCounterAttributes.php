<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Cron;

class RefreshCounterAttributes
{
    protected $attributeFactory;

    protected $eavConfig;

    protected $logger;

    protected $newAttributeByOptionIds = [];

    protected $setupInterface;

    protected $eavSetupFactory;

    protected $productCollection;

    protected $resourceConfig;

    protected $productRepository;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Setup\ModuleDataSetupInterface $setupInterface,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collection,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->attributeFactory = $attributeFactory;
        $this->eavConfig = $eavConfig;
        $this->logger = $logger;
        $this->setupInterface = $setupInterface;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productCollection = $collection;
        $this->resourceConfig = $resourceConfig;
        $this->productRepository = $productRepository;
    }

    public function refreshAttributeCounter($attributeCode, $counterAttributeCounter)
    {
        $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
        $attributeLabel = $attribute->getStoreLabel();
        $options = $attribute->getSource()->getAllOptions();
        $existingOptions = [];
        $existingOptionAttributes = [];
        foreach ($options as $op) {
            if (!$op["value"]) continue;
            $existingOptions[$op["value"]] = $op["label"];
        }

        $attributeCol = $this->attributeFactory->getCollection()
            ->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4)
            ->addFieldToFilter('attribute_code', ["like" =>  new \Zend_Db_Expr("'{$counterAttributeCounter}_%'")]);

        foreach ($attributeCol as $aItem) {
            $aItemCode = $aItem->getAttributeCode();
            $aItemCodeArray = explode("_", $aItemCode);
            $aItemOptionId = 0;
            if (isset($aItemCodeArray[2])) {
                $aItemOptionId = $aItemCodeArray[2];
            }
            if (!$aItemOptionId) continue;

            if (!in_array($aItemOptionId, array_keys($existingOptions))) {
                try {
                    $aItem->delete();
                    $this->logger->info("*** Removed removed unused attribute {$aItemCode}");
                } catch (\Exception $e) {
                    $this->logger->info("*** Error removing {$aItemCode}: " . $e->getMessage());
                }
            } else {
                $existingOptionAttributes[] = $aItemOptionId;
            }
        }

        $this->newAttributeByOptionIds[$attributeCode] = array_diff(array_keys($existingOptions), $existingOptionAttributes);
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setupInterface]);
        foreach ($this->newAttributeByOptionIds[$attributeCode] as $opId) {
            try {
                $newAttributeCode = $counterAttributeCounter . "_" . $opId;
                $newAttributeLabel = $attributeLabel . "s Counter - " . $existingOptions[$opId];
                $attributeLabel = $attribute->getStoreLabel();
                $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $newAttributeCode, [
                    'group' => 'Sorting Counters - ' . $attributeLabel,
                    'type' => 'varchar',
                    'label' => $newAttributeLabel,
                    'input' => 'text',
                    'required' => false,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'user_defined' => true,
                    'used_for_sort_by' => true,
                    'is_visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'default' => '999',
                    'apply_to' => ''
                ]);
                $this->logger->info("*** Created attribute {$newAttributeCode}");
            } catch (\Exception $e) {
                $this->logger->info("*** Error creating attribute {$newAttributeCode}: " . $e->getMessage());
            }
        }
    }

    public function addNewAttributeCountersInProducts($attributeCode, $counterAttributeCounter)
    {
        $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
        $pev = $this->setupInterface->getTable('catalog_product_entity_varchar');
        $connection = $this->resourceConfig->getConnection();
        foreach ($this->newAttributeByOptionIds[$attributeCode] as $opId) {
            $newAttributeCode = $counterAttributeCounter . "_" . $opId;
            $attributeCounter = $this->eavConfig->getAttribute('catalog_product', $newAttributeCode);
            if (!$attributeCounter->getId()) {
                $this->logger->info("*** Attribute {$newAttributeCode} does not exist.");
                continue;
            }
            $prodCol = $this->productCollection->create();
            $prodSelect = $prodCol->getSelect();
            $prodSelect->join(
                ["pev" => $pev],
                "e.entity_id = pev.entity_id AND pev.store_id = 0 AND pev.attribute_id = " . $attribute->getId(),
                []
            );
            $prodSelect->reset(\Zend_Db_Select::COLUMNS)
                ->columns([
                    "value_id" => new \Zend_Db_Expr("NULL"),
                    "attribute_id" => new \Zend_Db_Expr("{$attributeCounter->getId()}"),
                    "store_id" => "pev.store_id",
                    "entity_id" => "pev.entity_id",
                    "value" => new \Zend_Db_Expr("CASE WHEN (LENGTH(pev.value) - LENGTH(REPLACE(pev.value, ',', ''))) IS NULL THEN '999' ELSE LPAD((LENGTH(pev.value) - LENGTH(REPLACE(pev.value, ',', ''))) + 1, 3, '0') END")
                ]);
            $prodSelect->where("e.type_id = 'simple'");
            $prodSelect->where("pev.value LIKE '{$opId}' OR pev.value LIKE '%,{$opId},%'
                    OR pev.value LIKE '{$opId},%' OR pev.value LIKE '%,{$opId}'");

            $mysqlQuery = "INSERT INTO {$pev} (value_id, attribute_id, store_id, entity_id, value)"
                . " {$prodSelect->__toString()};";
            $connection->query($mysqlQuery);
            $this->logger->info("*** Updated attribute {$newAttributeCode} in all products.");
        }
    }

    public function updateCountersInParents($attributeCode, $counterAttributeCounter)
    {
        $prodCol = $this->productCollection->create();
        $prodCol->addFieldToFilter("type_id", ["eq" => "configurable"]);
        $saveProduct = false;
        foreach ($prodCol as $prodItem) {
            $product = $this->productRepository->getById($prodItem->getId());
            $children = $product->getTypeInstance()->getUsedProducts($product);

            $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
            $options = $attribute->getSource()->getAllOptions();
            foreach ($options as $op) {
                $attributeCounterOptionCode = $counterAttributeCounter;
                if ($op["value"]) {
                    $attributeCounterOptionCode .= "_" . $op["value"];
                }
                $counters = [];
                foreach ($children as $child) {
                    $acValues = $child->getData($attributeCounterOptionCode);
                    if ($acValues) {
                        $counters[] = $acValues;
                    } else {
                        $counters[] = "999";
                    }
                }
                if (count($counters)) {
                    if ((string)$product->getData($attributeCounterOptionCode) == (string)min($counters)) {
                        continue;
                    }
                    $this->logger->info("*** Product {$product->getSku()} - Changed attribute {$attributeCounterOptionCode} from {$product->getData($attributeCounterOptionCode) } to " . min($counters) . ".");
                    $product->setData($attributeCounterOptionCode, min($counters));
                    $saveProduct = true;
                }
            }
            if ($saveProduct) {
                $this->productRepository->save($product);
                $this->logger->info("*** Product {$product->getSku()} - Updated counters.");
            }
        }
    }

    public function refreshAttributes()
    {
        $this->refreshAttributeCounter("holiday", "holidays_counter");
        $this->refreshAttributeCounter("season", "seasons_counter");
        $this->addNewAttributeCountersInProducts("holiday", "holidays_counter");
        $this->addNewAttributeCountersInProducts("season", "seasons_counter");
        $this->updateCountersInParents("holiday", "holidays_counter");
        $this->updateCountersInParents("season", "seasons_counter");
    }

    /**
     * Update counter product attributes like holiday and season counters
     */
    public function execute()
    {
        $this->refreshAttributes();
    }
}
