<?php

namespace Uzer\Catalogs\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $connection = $setup->getConnection();
        $connection->dropTable($connection->getTableName('uzer_catalogs'));
        $installer->startSetup();
        /**
         * Create table 'uzer_catalogs_store'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('uzer_catalogs'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Catalog ID'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Catalog name'
            )->addColumn(
                'image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Catalog image'
            )->addColumn(
                'image_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Catalog image'
            )->addColumn(
                'link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Catalog image'
            )->addColumn(
                'active',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => true],
                'Is Post Active'
            )->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                15555555,
                ['nullable' => false],
                'Description catalog'
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('uzer_catalogs'),
                    ['image', 'image_name', 'link', 'description'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['image', 'image_name', 'link', 'description'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            );
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()->newTable(
            $installer->getTable('uzer_catalogs_store'))
            ->addColumn(
                'catalogs_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => true],
                'Post ID'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store ID'
            )->addIndex(
                $installer->getIdxName('uzer_catalogs_store', ['store_id']),
                ['store_id']
            )->addForeignKey(
                $installer->getFkName('uzer_catalogs_store', 'catalogs_id', 'uzer_catalogs', 'entity_id'),
                'catalogs_id',
                $installer->getTable('uzer_catalogs'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Uzer catalog to store linkage table');
        $installer->getConnection()->createTable($table);
    }
}
