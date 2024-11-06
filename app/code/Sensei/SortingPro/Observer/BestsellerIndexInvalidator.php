<?php

namespace Sensei\SortingPro\Observer;

use Sensei\SortingPro\Model\Indexer\Bestsellers\BestsellersProcessor;
use Magento\Framework\Event\ObserverInterface;

/**
 * observer name: bestsellers_index_invalidate
 * event names:
 *     sales_order_place_after
 *     order_cancel_after
 *     sales_order_state_change_before
 */
class BestsellerIndexInvalidator implements ObserverInterface
{

    private $indexProcessor;
    public function __construct(BestsellersProcessor $indexProcessor)
    {
        $this->indexProcessor = $indexProcessor;
    }

    /**
     * Mark Bestsellers indexer as invalid on event process
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->indexProcessor->markIndexerAsInvalid();
    }
}
