<?php

namespace Sensei\SortingPro\Observer;

use Sensei\SortingPro\Model\Indexer\MostViewed\MostViewedProcessor;
use Magento\Framework\Event\ObserverInterface;

/**
 * observer name: most_viewed_index_invalidate
 * event names:
 *     catalog_controller_product_view
 */
class ViewedIndexInvalidator implements ObserverInterface
{

    private $indexProcessor;

    public function __construct(MostViewedProcessor $indexProcessor)
    {
        $this->indexProcessor = $indexProcessor;
    }

    /**
     * Mark MostViewed indexer as invalid on event process
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->indexProcessor->markIndexerAsInvalid();
    }
}
