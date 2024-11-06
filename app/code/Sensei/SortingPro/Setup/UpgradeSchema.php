<?php
namespace Sensei\SortingPro\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    use TableInitTrate;

    private $renameLabelsField;

    private $deleteYotpoTable;

    public function __construct(
        Operation\RenameLabelsField $renameLabelsField,
        Operation\DeleteYotpoTable $deleteYotpoTable
    ) {
        $this->renameLabelsField = $renameLabelsField;
        $this->deleteYotpoTable = $deleteYotpoTable;
    }

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $bestsellersTable = $setup->getTable('sensei_sortingpro_bestsellers');
        $mostViewedTable = $setup->getTable('sensei_sortingpro_most_viewed');
        $wishedTable = $setup->getTable('sensei_sortingpro_wished');

        if ($context->getVersion() && version_compare($context->getVersion(), '2.0.0', '<')) {
            if (!$setup->getConnection()->isTableExists($bestsellersTable)) {
                /**
                 * Create table 'sensei_sorting_bestsellers'
                 */
                $this->createBestsellers($setup, $bestsellersTable);
                if ($setup->getConnection()->isTableExists($setup->getTable('scsorting_bestsellers'))) {
                    $setup->getConnection()->dropTable('scsorting_bestsellers');
                }
            }

            if (!$setup->getConnection()->isTableExists($mostViewedTable)) {
                /**
                 * Create table 'sensei_sorting_most_viewed'
                 */
                $this->createMostViewed($setup, $mostViewedTable);
                if ($setup->getConnection()->isTableExists($setup->getTable('scsorting_most_viewed'))) {
                    $setup->getConnection()->dropTable('scsorting_most_viewed');
                }
            }

            if (!$setup->getConnection()->isTableExists($wishedTable)) {
                /**
                 * Create table 'sensei_sorting_wished'
                 */
                $this->createWished($setup, $wishedTable);
                if ($setup->getConnection()->isTableExists($setup->getTable('scsorting_wished'))) {
                    $setup->getConnection()->dropTable('scsorting_wished');
                }
            }
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '2.0.1', '<')) {
            $this->addPrimaryKeys($setup, [$bestsellersTable, $mostViewedTable, $wishedTable]);
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '2.5.2', '<')) {
            $this->renameLabelsField->execute($setup);
        }

        if (version_compare($context->getVersion(), '2.8.2', '<')) {
            $this->deleteYotpoTable->execute($setup);
        }

        $setup->endSetup();
    }

    /**
     * Set columns product_id, store_id as primary keys
     *
     * @param SchemaSetupInterface $setup
     * @param array $tables
     */
    private function addPrimaryKeys(SchemaSetupInterface $setup, array $tables)
    {
        foreach ($tables as $table) {
            if ($setup->getConnection()->isTableExists($table)) {
                $setup->getConnection()->changeColumn(
                    $table,
                    'product_id',
                    'product_id',
                    ['type' => Table::TYPE_INTEGER, 'primary' => true],
                    'Product ID'
                );
                $setup->getConnection()->changeColumn(
                    $table,
                    'store_id',
                    'store_id',
                    ['type' => Table::TYPE_SMALLINT, 'primary' => true],
                    'Store Id'
                );
            }
        }
    }
}
