<?php

namespace Sensei\SortingPro\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    use TableInitTrate;

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $bestsellersTable = $setup->getTable('sensei_sortingpro_bestsellers');
        $mostViewedTable = $setup->getTable('sensei_sortingpro_most_viewed');
        $wishedTable = $setup->getTable('sensei_sortingpro_wished');

        /**
         * Create table 'sensei_sortingpro_bestsellers'
         */
        $this->createBestsellers($setup, $bestsellersTable);

        /**
         * Create table 'sensei_sortingpro_most_viewed'
         */
        $this->createMostViewed($setup, $mostViewedTable);

        /**
         * Create table 'sensei_sortingpro_wished'
         */
        $this->createWished($setup, $wishedTable);

        $setup->endSetup();
    }
}
