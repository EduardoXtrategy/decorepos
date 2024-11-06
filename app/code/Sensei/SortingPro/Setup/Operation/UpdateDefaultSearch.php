<?php

namespace Sensei\SortingPro\Setup\Operation;

use Magento\Catalog\Model\Config;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpdateDefaultSearch
{

    public function execute(ModuleDataSetupInterface $setup)
    {
        $updateData = [];
        $connection = $setup->getConnection();
        $tableName = $setup->getTable('core_config_data');

        $select = $setup->getConnection()->select()
            ->from($tableName, ['path', 'value', 'scope', 'scope_id'])
            ->where('path = ?', Config::XML_PATH_LIST_DEFAULT_SORT_BY);

        $rows = $connection->fetchAll($select);
        foreach ($rows as $row) {
            $updateData[] = [
                'value' => $row['value'],
                'path'  => 'scsorting/default_sorting/category_1',
                'scope' => $row['scope'],
                'scope_id' => $row['scope_id']
            ];
        }

        if (!empty($updateData)) {
            $connection->insertOnDuplicate($tableName, $updateData);
        }
    }
}
