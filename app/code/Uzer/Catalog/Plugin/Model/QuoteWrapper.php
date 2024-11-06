<?php
namespace Uzer\Catalog\Plugin\Model;

use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Uzer\Catalog\Api\Data\PartialBoxInterfaceFactory;
use Uzer\Catalog\Api\PartialBoxQuoteInterface;
use Uzer\Catalog\Model\PartialBox;
use Uzer\Catalog\Model\ResourceModel\PartialBox\CollectionFactory;

class QuoteWrapper
{

    protected PartialBoxInterfaceFactory $partialBoxFactory;

    protected PartialBoxQuoteInterface $partialBoxQuote;

    protected CollectionFactory $collectionFactory;

    protected Session $session;

    protected Http $request;

    /**
     *
     * @param PartialBoxInterfaceFactory $partialBoxFactory
     * @param PartialBoxQuoteInterface $partialBoxQuote
     * @param CollectionFactory $collectionFactory
     * @param Session $session
     * @param Http $request
     */
    public function __construct(PartialBoxInterfaceFactory $partialBoxFactory, PartialBoxQuoteInterface $partialBoxQuote, CollectionFactory $collectionFactory, Session $session, Http $request)
    {
        $this->partialBoxFactory = $partialBoxFactory;
        $this->partialBoxQuote = $partialBoxQuote;
        $this->collectionFactory = $collectionFactory;
        $this->session = $session;
        $this->request = $request;
    }

    /**
     *
     * @throws LocalizedException
     */
    public function beforeAddProduct(Quote $quote, \Magento\Catalog\Model\Product $product, $request = null, $processMode = AbstractType::PROCESS_MODE_FULL)
    {
        $partialBoxInput = $this->request->getParam('partial_box');
        $customProductId = $this->request->getParam('custom_product_id');
        if ($partialBoxInput == 1 && $customProductId) {
            if ($quote->getId()) {
                $count = $this->collectionFactory->create()
                    ->addFieldToFilter('quote_id', array(
                    'eq' => $quote->getId()
                ))
                    ->addFieldToFilter('product_id', array(
                    'eq' => $customProductId
                ))
                    ->count();
                if ($count > 0) {
                    throw new LocalizedException(__('You can not add the partial box since it is already in the shopping cart.'));
                }
            }
        }
    }

    public function afterAddProduct(Quote $quote, Item $result)
    {
        $partialBoxInput = $this->request->getParam('partial_box');
        $customProductId = $this->request->getParam('custom_product_id');
        if ($partialBoxInput == 1 && $customProductId) {
            $partialBox = $this->partialBoxFactory->create();
            $partialBox->setProductId((int) $customProductId);
            $partialBox->setQuoteId((int) $quote->getId());
            $partialBox->setQuoteItemId((int) $result->getId());
            $partialBox->setQty((int) $this->request->getParam('partial_box_qty', 1));
            $this->partialBoxQuote->register($partialBox);
        }
    }

    /**
     *
     * @param Quote $quote
     * @param int|mixed $itemId
     * @return void
     * @throws \Exception
     */
    public function beforeRemoveItem(Quote $quote, $itemId)
    {
        // $item = $quote->getItemById($itemId);
        // /** @var PartialBox $partialBox */
        // $partialBox = $this->collectionFactory->create()
        // ->addFieldToFilter('quote_id', array('eq' => $quote->getId()))
        // ->addFieldToFilter('product_id', array('eq' => $item->getProduct()->getId()))->load()->getFirstItem();
        // if ($partialBox->hasData()) {
        // $this->collectionFactory->create()->getResource()->delete($partialBox);
        // }
    }
}
