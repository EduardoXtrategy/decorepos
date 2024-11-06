<?php

namespace Uzer\Catalog\Plugin\Model;

use Magento\Framework\App\Request\Http;
use Uzer\Catalog\Model\PartialBox;
use Uzer\Catalog\Model\ResourceModel\PartialBoxFactory as ResourceModelFactory;
use Uzer\Catalog\Model\ResourceModel\PartialBox\CollectionFactory;
use Uzer\Catalog\Model\PartialBoxFactory;
use Magento\Checkout\Model\Cart;

class CartWrapper
{
    protected PartialBoxFactory $partialBoxFactory;
    protected CollectionFactory $collectionFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected Http $request;

    /**
     * @param PartialBoxFactory $partialBoxFactory
     * @param CollectionFactory $collectionFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param Http $request
     */
    public function __construct(
        PartialBoxFactory    $partialBoxFactory,
        CollectionFactory    $collectionFactory,
        ResourceModelFactory $resourceModelFactory,
        Http                 $request
    )
    {
        $this->partialBoxFactory = $partialBoxFactory;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->request = $request;
    }


    /**
     * @throws \Exception
     */
    public function afterUpdateItems(Cart $subject, $result)
    {
        $cartRequest = $this->request->get('cart');
        if (is_array($cartRequest)) {
            foreach ($cartRequest as $id => $item) {
                if (isset($item['partial'])) {
                    $parent = $subject->getQuote()->getItemById($id);
                    if ($parent->getHasChildren()) {
                        foreach ($parent->getChildren() as $child) {
                            /** @var PartialBox $partialBox */
                            $partialBox = $this->collectionFactory->create()
                                ->addFieldToFilter('quote_id', array('eq' => $subject->getQuote()->getId()))
                                ->addFieldToFilter('product_id', array('eq' => $child->getProduct()->getId()))->load()->getFirstItem();
                            if ($partialBox->hasData()) {
                                $this->collectionFactory->create()->getResource()->delete($partialBox);
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}
