<?php

namespace Sensei\SortingPro\Setup\Operation;

use Magento\Framework\Setup\SchemaSetupInterface;

class RenameLabelsField
{

    public function execute(SchemaSetupInterface $setup)
    {
        $updateData = [];
        $connection = $setup->getConnection();
        $tableName = $setup->getTable('core_config_data');

        $select = $setup->getConnection()->select()
            ->from($tableName, ['path', 'value', 'scope', 'scope_id'])
            ->where('path = ?', 'scsorting/biggest_saving/label');

        $rows = $connection->fetchAll($select);
        foreach ($rows as $row) {
            $updateData[] = [
                'value' => $row['value'],
                'path'  => 'scsorting/saving/label',
                'scope' => $row['scope'],
                'scope_id' => $row['scope_id']
            ];
        }

        if (!empty($updateData)) {
            $connection->insertOnDuplicate($tableName, $updateData);
        }
    }
}
