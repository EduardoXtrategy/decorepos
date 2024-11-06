<?php

namespace Sensei\SortingPro\Api;

/**
 * Interface IndexMethodWrapper
 * @api
 */
interface IndexMethodWrapperInterface
{
    /**
     * @return \Sense\SortingPro\Api\IndexedMethodInterface
     */
    public function getSource();

    /**
     * @return \Magento\Framework\Indexer\ActionInterface
     */
    public function getIndexer();
}
