<?php

namespace Amasty\Amp\Setup\UpgradeSchema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddDataForCms
{
    public const CMS_PAGE_TABLE = 'cms_page';
    public const AMP_CONTENT_FIELD = 'amp_content';
    public const LENGTH = 16777210;

    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::CMS_PAGE_TABLE);
        $setup->getConnection()->addColumn(
            $table,
            self::AMP_CONTENT_FIELD,
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'default' => false,
                'length' => self::LENGTH,
                'comment' => 'Amp Content'
            ]
        );
    }
}
