<?php

namespace Sensei\SortingPro\Model\Indexer;

use Sensei\SortingPro\Helper\Data;
use Sensei\SortingPro\Model\Indexer\Bestsellers\BestsellersProcessor;
use Sensei\SortingPro\Model\Indexer\MostViewed\MostViewedProcessor;
use Sensei\SortingPro\Model\Indexer\Wished\WishedProcessor;
use Magento\Indexer\Model\Indexer;
use Magento\Indexer\Model\IndexerFactory;

class Summary
{
    /**
     * @var array
     */
    private $indexerIds = [
        BestsellersProcessor::INDEXER_ID,
        MostViewedProcessor::INDEXER_ID,
        WishedProcessor::INDEXER_ID
    ];

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var \Sensei\SortingPro\Model\MethodProvider
     */
    private $methodProvider;

    /**
     * @var IndexerFactory
     */
    private $indexerFactory;

    public function __construct(
        \Sensei\SortingPro\Helper\Data $helper,
        \Sensei\SortingPro\Model\MethodProvider $methodProvider,
        IndexerFactory $indexerFactory
    ) {
        $this->helper = $helper;
        $this->methodProvider = $methodProvider;
        $this->indexerFactory = $indexerFactory;
    }

    /**
     * @return void
     */
    public function reindexAll()
    {
        foreach ($this->indexerIds as $indexerId) {
            // do full reindex if method not disabled
            $this->loadIndexer($indexerId)->reindexAll();
        }
    }

    /**
     * @param int $indexerId
     *
     * @return Indexer
     */
    private function loadIndexer($indexerId)
    {
        return $this->indexerFactory->create()
            ->load($indexerId);
    }
}
