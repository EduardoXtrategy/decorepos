<?php

namespace Uzer\Infor\Model\Builder;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Uzer\Infor\Api\Data\ModelItemInterface;
use Uzer\Infor\Api\Data\ModelItemInterfaceFactory;
use Uzer\Infor\Api\Data\RequestModelInterface;
use Uzer\Infor\Api\Data\RequestModelInterfaceFactory;

class BuilderOrderItem
{
    protected RequestModelInterfaceFactory $customerModelFactory;
    protected ModelItemInterfaceFactory $customerItemFactory;
    protected Order $order;
    protected Order\Item $item;
    protected int $lineNumber;
    protected string $coNum;

    /**
     * @param RequestModelInterfaceFactory $customerModelFactory
     * @param ModelItemInterfaceFactory $customerItemFactory
     */
    public function __construct(
        RequestModelInterfaceFactory $customerModelFactory,
        ModelItemInterfaceFactory    $customerItemFactory
    )
    {
        $this->customerModelFactory = $customerModelFactory;
        $this->customerItemFactory = $customerItemFactory;
    }


    /**
     *
     *
     * @param Order $order
     * @param Item $item
     * @param int $lineNumber
     * @param string $coNum
     * @return RequestModelInterface
     */
    public function build(Order $order, Order\Item $item, int $lineNumber, string $coNum): RequestModelInterface
    {
        $this->order = $order;
        $this->item = $item;
        $this->lineNumber = $lineNumber;
        $model = $this->customerModelFactory->create();
        $this->coNum = $coNum;
        $model->setAction(1);
        $model->setItemId('PBT=[SLCoitems]');
        $model->appendProperty($this->buildCoNum());
        $model->appendProperty($this->buildCoLine());
        $model->appendProperty($this->buildItem());
        $model->appendProperty($this->buildQtyOrderedConv());
        $model->appendProperty($this->buildUM());
        $model->appendProperty($this->buildPriceConv());
        $model->appendProperty($this->buildDisc());
        $model->appendProperty($this->buildStat());
        $model->appendProperty($this->buildDueDate());
        $model->appendProperty($this->buildAllowOnPickList());
        $model->appendProperty($this->buildShipSite());
        $model->appendProperty($this->buildWhse());
        $model->appendProperty($this->buildRefType());
        return $model;
    }


    public function buildCoNum(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setIsNull(false);
        $item->setName('CoNum');
        $item->setValue($this->coNum);
        return $item;
    }

    public function buildCoLine(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setIsNull(false);
        $item->setName('CoLine');
        $item->setValue($this->lineNumber);
        return $item;
    }

    public function buildItem(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setIsNull(false);
        $item->setName('Item');
        $item->setValue($this->item->getSku());
        return $item;
    }

    public function buildQtyOrderedConv(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setIsNull(false);
        $item->setName('QtyOrderedConv');
        $item->setValue($this->item->getQtyOrdered());
        return $item;
    }

    public function buildUM(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setIsNull(false);
        $item->setName('UM');
        $item->setValue('EA');
        return $item;
    }

    public function buildPriceConv(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('PriceConv');
        $item->setValue($this->item->getPrice());
        return $item;
    }

    public function buildDisc(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('Disc');
        $item->setValue($this->item->getDiscountAmount());
        return $item;
    }

    public function buildcoiUf_decision_mak_u(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('coiUf_decision_mak_u');
        $item->setValue('1800F');
        return $item;
    }

    public function buildStat(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('Stat');
        $status = $this->order->getStatus() == Order::STATE_PROCESSING ? 'O' : 'P';
        $item->setValue($status);
        return $item;
    }

    public function buildDueDate(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('DueDate');
        $item->setValue($this->item->getCreatedAt());
        return $item;
    }

    public function buildAllowOnPickList(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('AllowOnPickList');
        $item->setValue('1');
        return $item;
    }

    public function buildShipSite(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('ShipSite');
        $item->setValue('SDWCOL');
        return $item;
    }

    public function buildWhse(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('Whse');
        $item->setValue('Main');
        return $item;
    }

    public function buildRefType(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('RefType');
        $item->setValue('I');
        return $item;
    }

}
