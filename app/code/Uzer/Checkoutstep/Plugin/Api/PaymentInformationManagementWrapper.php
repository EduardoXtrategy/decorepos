<?php

namespace Uzer\Checkoutstep\Plugin\Api;

use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\Quote;
use Uzer\Catalog\Logger\Logger;
use Uzer\Checkoutstep\Model\PurchaseOrder;
use Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrder\Collection;
use Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrderFactory as ResourceModel;
use Uzer\Checkoutstep\Model\PurchaseOrderFactory;

class PaymentInformationManagementWrapper
{

    protected ResourceModel $resourceModel;
    protected PurchaseOrderFactory $purchaseOrderFactory;
    protected CartRepositoryInterface $cartRepository;

    /**
     * @param ResourceModel $resourceModel
     * @param PurchaseOrderFactory $purchaseOrderFactory
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        ResourceModel           $resourceModel,
        PurchaseOrderFactory    $purchaseOrderFactory,
        CartRepositoryInterface $cartRepository
    )
    {
        $this->resourceModel = $resourceModel;
        $this->purchaseOrderFactory = $purchaseOrderFactory;
        $this->cartRepository = $cartRepository;
    }


    /**
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        PaymentInformationManagementInterface $subject,
                                              $cartId,
        PaymentInterface                      $paymentMethod,
        AddressInterface                      $billingAddress = null
    ): array
    {
        $logger = ObjectManager::getInstance()->get(Logger::class);
        $logger->info(__METHOD__);
        $logger->info($paymentMethod->getPoNumber());
        $collection = ObjectManager::getInstance()->create(Collection::class);
        /** @var PurchaseOrder $purchaseOrder */
        $purchaseOrder = $collection->addQuoteIdToFilter($cartId)->load()->getFirstItem();
        $resourceModel = $this->resourceModel->create();
        if (!$purchaseOrder->hasData() && $paymentMethod->getPoNumber()) {
            $purchaseOrder = $this->purchaseOrderFactory->create();
            $purchaseOrder->setQuoteId($cartId);
        }
        $purchaseOrder->setPoNumber($paymentMethod->getPoNumber());
        $resourceModel->save($purchaseOrder);
        return [$cartId, $paymentMethod, $billingAddress];
    }

}
