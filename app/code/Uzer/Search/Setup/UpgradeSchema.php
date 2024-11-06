<?php

namespace Uzer\Search\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $tableName = 'product_banner_store';
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->getConnection()->isTableExists($tableName)) {
            $table = $installer->getConnection()->newTable($installer->getTable($tableName))
                ->addColumn(
                    'product_banner_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'primary' => true],
                    'Product banner'
                )->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Store ID'
                );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
