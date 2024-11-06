<?php

namespace Uzer\Infor\Model\Builder;

use Magento\Sales\Model\Order;

use Uzer\Infor\Api\Data\ModelItemInterface;
use Uzer\Infor\Api\Data\ModelItemInterfaceFactory;
use Uzer\Infor\Api\Data\RequestModelInterfaceFactory;

class BuilderOrder
{

    protected RequestModelInterfaceFactory $customerModelFactory;
    protected ModelItemInterfaceFactory $customerItemFactory;
    protected Order $order;

    public function __construct(
        RequestModelInterfaceFactory $customerModelFactory,
        ModelItemInterfaceFactory    $customerItemFactory
    )
    {
        $this->customerModelFactory = $customerModelFactory;
        $this->customerItemFactory = $customerItemFactory;
    }

    public function build(Order $order)
    {
        $this->order = $order;
        $model = $this->customerModelFactory->create();
        $model->setAction(1);
        $model->setItemId('PBT=[SLCos]');
        $model->appendProperty($this->buildCoNum());
        $model->appendProperty($this->buildCustNum());
        $model->appendProperty($this->buildCustSeq());
        $model->appendProperty($this->buildStat());
        $model->appendProperty($this->buildOrderDate());
        $model->appendProperty($this->buildCustPo());
        $model->appendProperty($this->buildType());
        $model->appendProperty($this->buildWhse());
        $model->appendProperty($this->buildOrigSite());
        $model->appendProperty($this->buildCurrCode());
        $model->appendProperty($this->buildPrepaidAmt());
        $model->appendProperty($this->buildFreight());
        $model->appendProperty($this->buildMiscCharges());
        $model->appendProperty($this->buildTaxCode1());
        return $model;
    }


    public function buildCoNum(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('CoNum');
        $item->setValue("M?");
        return $item;
    }

    public function buildCustNum(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('CustNum');
        $formattedOrderId = str_pad('DB00001', 7, '0', STR_PAD_LEFT);
        $item->setValue($formattedOrderId);
        return $item;
    }

    public function buildCustSeq(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('CustSeq');
        $item->setValue("0");
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

    public function buildOrderDate(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('OrderDate');
        $item->setValue($this->order->getCreatedAt());
        return $item;
    }

    public function buildCustPo(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('CustPo');
        $item->setValue($this->order->getIncrementId());
        return $item;
    }

    public function buildType(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('Type');
        $item->setValue('R');
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

    public function buildOrigSite(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('OrigSite');
        $item->setValue('SDWCOL');
        return $item;
    }

    public function buildcoUfTrackingNum(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('coUfTrackingNum');
        $item->setValue($this->order->getIncrementId());
        return $item;
    }

    public function buildCurrCode(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('CurrCode');
        $item->setValue('USD');
        return $item;
    }

    public function buildPrepaidAmt(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('PrepaidAmt');
        $item->setValue('0');
        return $item;
    }

    public function buildFreight(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('Freight');
        $item->setValue('0');
        return $item;
    }

    public function buildMiscCharges(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('MiscCharges');
        $item->setValue('0');
        return $item;
    }

    public function buildTaxCode1(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setModified(true);
        $item->setModified(true);
        $item->setName('TaxCode1');
        $item->setValue('AA0002');
        return $item;
    }

}
