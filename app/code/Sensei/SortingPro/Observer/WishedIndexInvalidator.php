<?php

namespace Sensei\SortingPro\Observer;

use Sensei\SortingPro\Model\Indexer\Wished\WishedProcessor;
use Magento\Framework\Event\ObserverInterface;

/**
 * observer name: wished_index_invalidate
 * event names:
 *     wishlist_add_product
 */
class WishedIndexInvalidator implements ObserverInterface
{

    private $indexProcessor;

    /**
     * ViewedIndexInvalidator constructor.
     */
    public function __construct(WishedProcessor $indexProcessor)
    {
        $this->indexProcessor = $indexProcessor;
    }

    /**
     * Mark Wished indexer as invalid on event process
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->indexProcessor->markIndexerAsInvalid();
    }
}
