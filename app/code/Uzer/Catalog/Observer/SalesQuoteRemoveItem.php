<?php

namespace Uzer\Catalog\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Uzer\Catalog\Api\Data\PartialBoxInterfaceFactory;
use Uzer\Catalog\Api\PartialBoxQuoteInterface;
use Uzer\Catalog\Model\PartialBox;
use Uzer\Catalog\Model\ResourceModel\PartialBox\CollectionFactory;

class SalesQuoteRemoveItem implements ObserverInterface
{

    protected PartialBoxInterfaceFactory $partialBoxFactory;
    protected PartialBoxQuoteInterface $partialBoxQuote;
    protected CollectionFactory $collectionFactory;
    protected Http $request;

    /**
     * @param PartialBoxInterfaceFactory $partialBoxFactory
     * @param PartialBoxQuoteInterface $partialBoxQuote
     * @param CollectionFactory $collectionFactory
     * @param Http $request
     */
    public function __construct(
        PartialBoxInterfaceFactory $partialBoxFactory,
        PartialBoxQuoteInterface   $partialBoxQuote,
        CollectionFactory          $collectionFactory,
        Http                       $request)
    {
        $this->partialBoxFactory = $partialBoxFactory;
        $this->partialBoxQuote = $partialBoxQuote;
        $this->collectionFactory = $collectionFactory;
        $this->request = $request;
    }


    /**
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote\Item $item */
        $item = $observer->getData('quote_item');
        if ($item->getHasChildren()) {
            foreach ($item->getChildren() as $child) {
                /** @var PartialBox $partialBox */
                $partialBox = $this->collectionFactory->create()
                    ->addFieldToFilter('quote_id', array('eq' => $item->getQuoteId()))
                    ->addFieldToFilter('product_id', array('eq' => $child->getProduct()->getId()))->load()->getFirstItem();
                if ($partialBox->hasData()) {
                    $this->collectionFactory->create()->getResource()->delete($partialBox);
                }
            }
        }
    }
}