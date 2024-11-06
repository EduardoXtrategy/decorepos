<?php

namespace Sensei\SortingPro\Model;

use Sensei\SortingPro\Api\IndexMethodWrapperInterface;
use Sensei\SortingPro\Api\IndexedMethodInterface;
use Sensei\SortingPro\Model\Indexer\AbstractIndexer;


class IndexMethodWrapper implements IndexMethodWrapperInterface
{
    private $source;

    private $indexer;

    public function __construct(
        IndexedMethodInterface $source,
        AbstractIndexer $indexer
    ) {
        $this->source = $source;
        $this->indexer = $indexer;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getIndexer()
    {
        return $this->indexer;
    }
}
