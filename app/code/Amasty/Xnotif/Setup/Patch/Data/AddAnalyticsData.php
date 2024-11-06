<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Out of Stock Notification for Magento 2
 */

namespace Amasty\Xnotif\Setup\Patch\Data;

use Amasty\Xnotif\Api\Analytics\Data\StockInterface as Stock;
use Amasty\Xnotif\Model\ResourceModel\Stock\Subscription\Collection;
use Amasty\Xnotif\Model\ResourceModel\Stock\Subscription\CollectionFactory as StockCollectionFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddAnalyticsData implements DataPatchInterface
{
    /**
     * @var AdapterInterface|null
     */
    private $connection = null;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var StockCollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        StockCollectionFactory $collectionFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->collectionFactory = $collectionFactory;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        $this->connection = $this->moduleDataSetup->getConnection();
        $stockAnalyticsTable = $this->moduleDataSetup->getTable(Stock::MAIN_TABLE);
        $analyticDataSelect = $this->connection->select()->from($stockAnalyticsTable)->limit(1);

        if (!$this->connection->fetchCol($analyticDataSelect)) {
            $stockSubscriptions = $this->collectionFactory->create();

            $statistics = array_merge_recursive(
                $this->getCountByDate(clone $stockSubscriptions->getSelect(), 'add_date', 'subscribed'),
                $this->getCountByDate(clone $stockSubscriptions->getSelect(), 'send_date', 'sent'),
                $this->getOrders($stockSubscriptions)
            );

            foreach ($statistics as &$statistic) {
                if (is_array($statistic['date'])) {
                    $statistic['date'] = $statistic['date'][0];
                }
                if (!isset($statistic['subscribed'])) {
                    $statistic['subscribed'] = 0;
                }
                if (!isset($statistic['sent'])) {
                    $statistic['sent'] = 0;
                }
                if (!isset($statistic['orders'])) {
                    $statistic['orders'] = 0;
                }
            }

            if ($statistics) {
                $this->connection->insertMultiple(
                    $stockAnalyticsTable,
                    $statistics
                );
            }
        }

        return $this;
    }

    private function getCountByDate(Select $select, string $fieldDate, string $alias): array
    {
        $select
            ->reset(Select::COLUMNS)
            ->columns('count(*) as ' . $alias)
            ->columns('DATE(`' . $fieldDate . '`) as date')
            ->group('date')
            ->having('`date` IS NOT NULL');
        $result = $this->connection->fetchAll($select);

        return $this->updateResult($result);
    }

    private function getOrders(Collection $stockSubscriptions): array
    {
        $stockSubscriptions
            ->_renderFiltersBefore()
            ->joinSales(false)
            ->getSelect()
            ->reset(Select::COLUMNS)
            ->columns('DATE(sales.created_at) as date')
            ->columns('SUM(sales_item.base_row_total) as orders')
            ->group('date');
        $result = $this->connection->fetchAll($stockSubscriptions->getSelect());

        return $this->updateResult($result);
    }

    private function updateResult(array $result): array
    {
        foreach ($result as $key => $data) {
            if (isset($data['date'])) {
                $result[$data['date']] = $data;
                unset($result[$key]);
            }
        }

        return $result;
    }
}
