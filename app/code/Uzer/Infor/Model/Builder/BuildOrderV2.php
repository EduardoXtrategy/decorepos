<?php

namespace Uzer\Infor\Model\Builder;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Uzer\Infor\Api\Data\ModelItemInterface;
use Uzer\Infor\Api\Data\ModelItemInterfaceFactory;
use Uzer\Infor\Api\Data\RequestModelInterface;
use Uzer\Infor\Api\Data\RequestModelInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Sales\Api\Data\TransactionInterface;

class BuildOrderV2
{

    protected RequestModelInterfaceFactory $requestModelFactory;
    protected ModelItemInterfaceFactory $requestItemFactory;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected TransactionRepositoryInterface $transactionRepository;

    protected Order $order;
    protected Item $item;
    protected ?\Magento\Sales\Model\Order\Payment\Transaction $transaction = null;


    /**
     * @param RequestModelInterfaceFactory $requestModelFactory
     * @param ModelItemInterfaceFactory $requestItemFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(
        RequestModelInterfaceFactory   $requestModelFactory,
        ModelItemInterfaceFactory      $requestItemFactory,
        SearchCriteriaBuilder          $searchCriteriaBuilder,
        TransactionRepositoryInterface $transactionRepository
    )
    {
        $this->requestModelFactory = $requestModelFactory;
        $this->requestItemFactory = $requestItemFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->transactionRepository = $transactionRepository;
    }


    public function build(Order $order, Item $item): RequestModelInterface
    {
        $this->order = $order;
        $this->item = $item;
        $this->loadTransaction();
        $requestModel = $this->requestModelFactory->create();
        $requestModel->setAction(1);
        $requestModel->setItemId('PBT=[ue_DWP_MagnetoStgCOs]');
        $requestModel->appendProperty($this->buildMagentoCustID());
        $requestModel->appendProperty($this->buildMagnetoAddressID());
        $requestModel->appendProperty($this->buildMagentoWhse());
        $requestModel->appendProperty($this->buildMagnetoOrderID());
        $requestModel->appendProperty($this->buildMagentoPO());
        $requestModel->appendProperty($this->buildMagentoOrderDate());
        $requestModel->appendProperty($this->buildSpecialInstructions());
        $requestModel->appendProperty($this->buildMagentoItem());
        $requestModel->appendProperty($this->buildOrderedQty());
        $requestModel->appendProperty($this->buildPrice());
        $requestModel->appendProperty($this->buildMagentoLineID());
        $requestModel->appendProperty($this->buildTerms());
        $requestModel->appendProperty($this->buildShipVia());
        $requestModel->appendProperty($this->buildTransactionID());
        return $requestModel;
    }

    public function loadTransaction()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $this->order->getId())
            ->create();
        /** @var \Magento\Sales\Model\ResourceModel\Order\Payment\Transaction\Collection|\Magento\Sales\Api\Data\TransactionSearchResultInterface $transactions */
        $transactions = $this->transactionRepository->getList($searchCriteria);
        /** @var TransactionInterface|\Magento\Sales\Model\Order\Payment\Transaction $item */
        $item = $transactions->load()->getFirstItem();
        if ($item && $item->getId()) {
            $this->transaction = $item;
        }
    }

    public function buildMagentoCustID(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagentoCustID');
        $customerItem->setValue($this->order->getCustomerId());
        $customerItem->setIsNull(false);
        return $customerItem;
    }


    public function buildMagnetoAddressID(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagnetoAddressID');
        $customerItem->setValue($this->order->getShippingAddress()->getCustomerAddressId());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildMagentoWhse(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagentoWhse');
        $customerItem->setValue('36-2');
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildMagnetoOrderID(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagnetoOrderID');
        $customerItem->setValue($this->order->getId());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildMagentoPO(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagentoPO');
        $customerItem->setValue($this->order->getIncrementId());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildMagentoOrderDate(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagentoOrderDate');
        $customerItem->setValue($this->order->getCreatedAt());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildSpecialInstructions(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('SpecialInstructions');
        $customerItem->setValue($this->order->getCustomerNote());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildMagentoItem(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagentoItem');
        $customerItem->setValue($this->item->getSku());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildOrderedQty(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('OrderedQty');
        $customerItem->setValue($this->item->getQtyOrdered());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildPrice(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('Price');
        $customerItem->setValue($this->item->getPrice());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildMagentoLineID(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('MagentoLineID');
        $customerItem->setValue($this->item->getId());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildTerms(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('Terms');
        $customerItem->setValue($this->buildCodeTerms());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    public function buildCodeTerms(): string
    {

        $method = $this->order->getPayment()->getMethod();
        $dictionary = [
            'terms' => 'NET',
            'stripe_payments' => 'STP',
            'mercadopago' => 'MPG'
        ];
        return $dictionary[$method] ?? '';
    }

    public function buildShipVia(): ModelItemInterface
    {
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('ShipVia');
        $customerItem->setValue($this->buildCodeShipVia());
        $customerItem->setIsNull(false);
        return $customerItem;
    }

    private function buildCodeShipVia(): string
    {
        $shippingMethod = $this->order->getShippingMethod();
        $dictionary = [
            'amstrates_amstrates4' => '62',
            'amstorepick_amstorepick1' => '64',
            'deliverylatam_deliverylatam' => '60',
            'amstorepick_amstorepick4' => '63',
            'amstorepick_amstorepick2' => '64',
            'amstrates_amstrates1' => '61',
        ];
        return $dictionary[$shippingMethod] ?? '';
    }

    public function buildTransactionID(): ModelItemInterface
    {
        $transactionId = $this->getTransactionId($this->order);
        $customerItem = $this->requestItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('TransactionID');
        $customerItem->setValue($transactionId);
        $customerItem->setIsNull(is_null($transactionId));
        return $customerItem;
    }

    public function getTransactionId(Order $order): ?string
    {
        if ($this->transaction && $this->transaction->getId()) {
            return $this->transaction->getTxnId();
        }
        return null;
    }
}
