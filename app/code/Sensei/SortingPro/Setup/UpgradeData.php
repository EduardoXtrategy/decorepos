<?php

namespace Sensei\SortingPro\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Upgrade Data script
 * @codingStandardsIgnoreFile
 */
class UpgradeData implements UpgradeDataInterface
{

    private $resourceConfig;

    private $scopeConfig;

    protected $eavSetupFactory;
    private $productCollection;
    private $productAction;
    protected $messageManager;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    public function __construct(
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collection,
        \Magento\Catalog\Model\Product\Action $action,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->resourceConfig = $resourceConfig;
        $this->scopeConfig = $scopeConfig;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productCollection = $collection;
        $this->productAction = $action;
        $this->messageManager = $messageManager;
        $this->eavConfig = $eavConfig;
    }

    protected function addCounterAttributes($setup, $eavSetup, $attributeCode, $attributeCounterCode)
    {
        $ci = 0;
        try {
            $connection = $this->resourceConfig->getConnection();
            $pr = $setup->getTable('catalog_product_relation');
            $pev = $setup->getTable('catalog_product_entity_varchar');
            $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
            $options = $attribute->getSource()->getAllOptions();
            foreach ($options as $op) {
                $attributeLabel = $attribute->getStoreLabel();
                $newAttributeCode = $attributeCounterCode;
                $newAttributeLabel = $attributeLabel . "s Counter";
                if ($op["value"]) {
                    $newAttributeCode = $attributeCounterCode . "_" . $op["value"];
                    $newAttributeLabel .= " - " . $op["label"];
                }
                try {
                    $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $newAttributeCode, [
                        'group' => 'Sorting Counters - ' . $attributeLabel,
                        'type' => 'varchar',
                        'label' => $newAttributeLabel,
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => $ci,
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
                } catch (\Exception $e) {
                    //Ignoring errors when attributes already exist
                }
                $attributeCounter = $this->eavConfig->getAttribute('catalog_product', $newAttributeCode);
                if ($attributeCounter->getId()) {
                    $mysqlQuery = "DELETE FROM {$pev}"
                        . " WHERE attribute_id = {$attributeCounter->getId()};";
                    $connection->query($mysqlQuery);
                } else {
                    throw new \Exception("Attribute {$newAttributeCode} does not exist.");
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
                if ($op["value"]) {
                    $prodSelect->where("pev.value LIKE '{$op["value"]}' OR pev.value LIKE '%,{$op["value"]},%'
                        OR pev.value LIKE '{$op["value"]},%' OR pev.value LIKE '%,{$op["value"]}'");
                }

                $mysqlQuery = "INSERT INTO {$pev} (value_id, attribute_id, store_id, entity_id, value)"
                    . " {$prodSelect->__toString()};";
                $connection->query($mysqlQuery);

                $prodCol = $this->productCollection->create();
                $prodSelect = $prodCol->getSelect();
                $prodSelect->join(
                    ["pr" => $pr],
                    "e.entity_id = pr.parent_id",
                    []
                )->join(
                    ["pev" => $pev],
                    "pr.child_id = pev.entity_id AND pev.store_id = 0 AND pev.attribute_id = " . $attributeCounter->getId(),
                    []
                );
                $prodSelect->reset(\Zend_Db_Select::COLUMNS)
                    ->columns([
                        "value_id" => new \Zend_Db_Expr("NULL"),
                        "attribute_id" => new \Zend_Db_Expr("{$attributeCounter->getId()}"),
                        "store_id" => "pev.store_id",
                        "entity_id" => "e.entity_id",
                        "value" => new \Zend_Db_Expr("MIN(pev.value)")
                    ]);
                $orString = "";
                $prodSelect->where("e.type_id = 'configurable'");
                if ($op["value"]) {
                    $orString = "SELECT DISTINCT entity_id FROM catalog_product_entity_varchar
                            WHERE attribute_id = {$attribute->getId()} AND (value LIKE '{$op["value"]}'
                                OR value LIKE '%,{$op["value"]},%' OR value LIKE '{$op["value"]},%' OR value LIKE '%,{$op["value"]}')";
                    $prodSelect->where("pr.child_id IN (?)", new \Zend_Db_Expr($orString));
                }
                $prodSelect->group(["pev.store_id", "e.entity_id", "pev.attribute_id"]);

                $mysqlQuery = "INSERT INTO {$pev} (value_id, attribute_id, store_id, entity_id, value)"
                    . " {$prodSelect->__toString()};";
                $connection->query($mysqlQuery);

                $prodCol = $this->productCollection->create();
                $prodSelect = $prodCol->getSelect();
                $prodSelect->joinLeft(
                    ["pev" => $pev],
                    "e.entity_id = pev.entity_id AND pev.store_id = 0 AND pev.attribute_id = " . $attributeCounter->getId(),
                    []
                );
                $prodSelect->reset(\Zend_Db_Select::COLUMNS)
                    ->columns([
                        "value_id" => new \Zend_Db_Expr("NULL"),
                        "attribute_id" => new \Zend_Db_Expr("{$attributeCounter->getId()}"),
                        "store_id" => "pev.store_id",
                        "entity_id" => "e.entity_id",
                        "value" => new \Zend_Db_Expr("'999'")
                    ]);
                $orString = "";
                $prodSelect->where("pev.entity_id IS NULL");

                $mysqlQuery = "INSERT INTO {$pev} (value_id, attribute_id, store_id, entity_id, value)"
                    . " {$prodSelect->__toString()};";
                $connection->query($mysqlQuery);

                $ci += 10;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if ($context->getVersion() && version_compare($context->getVersion(), '2.0.0', '<')) {
            $path = 'scsorting/general/desc_attributes';
            $connection = $this->resourceConfig->getConnection();

            $select = $connection->select()->from(
                $this->resourceConfig->getMainTable()
            )->where(
                'path = ?',
                $path
            );

            $rowSet = $connection->fetchAll($select);
            $defaultDesc = 'bestsellers,rating_summary,reviews_count,most_viewed,wished,created_at,saving';

            if (count($rowSet)) {
                $idName = $this->resourceConfig->getIdFieldName();
                foreach ($rowSet as $row) {
                    $value = '';
                    if ($row['value']) {
                        // prepare old values
                        $value = ',' .  str_replace(' ', '', $row['value']);
                    }
                    $row['value'] = $defaultDesc . $value;
                    $whereCondition = [$idName . '=?' => $row[$idName]];
                    $connection->update($this->resourceConfig->getMainTable(), $row, $whereCondition);
                }
            }
        }

        if (version_compare($context->getVersion(), "2.8.5", "<")) {
            //Updating Holiday and Season Counters on existing products
            $this->addCounterAttributes($setup, $eavSetup, "holiday", "holidays_counter");
            $this->addCounterAttributes($setup, $eavSetup, "season", "seasons_counter");
        }

        $setup->endSetup();
    }
}
