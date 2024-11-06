<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Setup\Operation;

use Magento\Framework\Setup\SchemaSetupInterface;

class DeleteYotpoTable
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $oldTable = 'sensei_sortingpro_yotpo';
        if ($setup->tableExists($oldTable)) {
            $setup->getConnection()->dropTable($setup->getTable($oldTable));
        }
    }
}
