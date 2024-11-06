<?php

namespace Uzer\Checkoutstep\Plugin\Api;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Webapi\Request;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Model\Quote;
use Uzer\Catalog\Logger\Logger;
use Uzer\Checkoutstep\Model\PurchaseOrder;
use Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrderFactory as ResourceModel;
use Uzer\Checkoutstep\Model\PurchaseOrderFactory;

class CartRepositoryWrapper
{

    protected ResourceModel $resourceModel;
    protected PurchaseOrderFactory $purchaseOrderFactory;

    /**
     * @param ResourceModel $resourceModel
     * @param PurchaseOrderFactory $purchaseOrderFactory
     */
    public function __construct(ResourceModel $resourceModel, PurchaseOrderFactory $purchaseOrderFactory)
    {
        $this->resourceModel = $resourceModel;
        $this->purchaseOrderFactory = $purchaseOrderFactory;
    }


    /**
     * @param CartRepositoryInterface $cartRepository
     * @param CartInterface|Quote $quote
     * @return array
     */
    public function beforeSave(CartRepositoryInterface $cartRepository, CartInterface $quote): array
    {
        if ($quote->getId()) {
            $collection = ObjectManager::getInstance()->create(\Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrder\Collection::class);
            /** @var PurchaseOrder $purchaseOrder */
            $purchaseOrder = $collection->addQuoteIdToFilter($quote->getId())->load()->getFirstItem();
            if ($purchaseOrder->hasData()) {
                $quote->getExtensionAttributes()->setPurchaseOrder($purchaseOrder->getPoNumber());
                $quote->setData('purchase_order', $purchaseOrder->getPoNumber());
                if ($quote->getPayment()) {
                    $quote->getPayment()->setPoNumber($purchaseOrder->getPoNumber());
                }
            }
        }
        return [$quote];
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface|Quote $cart
     * @return CartInterface
     */
    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface           $cart
    ): CartInterface
    {
        $purchaseOrder = $cart->getData('purchase_order');
        $cart->getExtensionAttributes()->setPurchaseOrder($purchaseOrder);
        return $cart;
    }


    /**
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $searchResult
     * @return CartSearchResultsInterface
     */
    public function afterGetList(
        CartRepositoryInterface    $subject,
        CartSearchResultsInterface $searchResult
    ): CartSearchResultsInterface
    {
        foreach ($searchResult->getItems() as $quote) {
            $this->afterGet($subject, $quote);
        }
        return $searchResult;
    }

}
