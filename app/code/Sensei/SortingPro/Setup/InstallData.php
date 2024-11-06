<?php

namespace Sensei\SortingPro\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Indexer\Model\IndexerFactory;
use Magento\Indexer\Model\Indexer;
use Sensei\SortingPro\Model\Indexer\Bestsellers\BestsellersProcessor;
use Sensei\SortingPro\Model\Indexer\MostViewed\MostViewedProcessor;
use Sensei\SortingPro\Model\Indexer\Wished\WishedProcessor;
use Magento\Framework\App\State;

class InstallData implements InstallDataInterface
{

    private $indexer;

    private $indexerIds = [
        BestsellersProcessor::INDEXER_ID,
        MostViewedProcessor::INDEXER_ID,
        WishedProcessor::INDEXER_ID
    ];

    private $state;

    private $defaultSearch;

    public function __construct(
        IndexerFactory $indexer,
        State $state,
        Operation\UpdateDefaultSearch $defaultSearch
    ) {
        $this->state = $state;
        $this->indexer = $indexer;
        $this->defaultSearch = $defaultSearch;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->state->emulateAreaCode(
            'adminhtml',
            [$this, 'reindexAll']
        );

        $this->defaultSearch->execute($setup);
    }

    public function reindexAll()
    {
        foreach ($this->indexerIds as $indexerId) {
            $this->loadIndexer($indexerId)->reindexAll();
        }
    }

    private function loadIndexer($indexerId)
    {
        return $this->indexer->create()
            ->load($indexerId);
    }
}
