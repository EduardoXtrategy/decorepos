<?php

namespace Uzer\Infor\Model\Builder;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Uzer\Infor\Api\Data\RequestModelInterface;
use Uzer\Infor\Api\Data\RequestModelInterfaceFactory;
use Uzer\Infor\Api\Data\ModelItemInterfaceFactory;
use Uzer\Infor\Api\Data\ModelItemInterface;

class BuildCancelOrderItem
{

    protected RequestModelInterfaceFactory $customerModelFactory;
    protected ModelItemInterfaceFactory $customerItemFactory;
    protected Order $order;
    protected Item $item;


    public function __construct(
        RequestModelInterfaceFactory $customerModelFactory,
        ModelItemInterfaceFactory    $customerItemFactory
    ) {
        $this->customerModelFactory = $customerModelFactory;
        $this->customerItemFactory = $customerItemFactory;
    }


    /**
     *
     *
     * @param Order $order
     * @param Item $item
     * @param int $lineNumber
     * @param string $lineNumber
     * @return RequestModelInterface
     */
    public function build(Order $order, Item $item, string $lineId, string $coNum, string $lineNumber, string $coRelease): RequestModelInterface
    {
        $this->order = $order;
        $this->item = $item;
        $model = $this->customerModelFactory->create();
        $model->setAction(2);
        $model->setItemId($lineId);
        $model->appendProperty($this->buildCoNum($coNum));
        $model->appendProperty($this->buildCoLine($lineNumber));
        $model->appendProperty($this->buildCoRelease($coRelease));
        $model->appendProperty($this->buildCoiUf_Cancel());
        return $model;
    }

    public function buildCoNum(string $coNum): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setName('CoNum');
        $item->setValue($coNum);
        $item->setModified(false);
        $item->setIsNull(false);
        return $item;
    }

    public function buildCoLine( string $lineNumber): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setName('CoNum');
        $item->setValue($this->order->getIncrementId());
        $item->setModified(false);
        $item->setIsNull(false);
        return $item;
    }

    public function buildCoRelease(string $coRelease): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setName('CoNum');
        $item->setValue($this->order->getIncrementId());
        $item->setModified(false);
        $item->setIsNull(false);
        return $item;
    }

    public function buildCoiUf_Cancel(): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setName('CoNum');
        $item->setValue($this->order->getIncrementId());
        $item->setModified(true);
        $item->setIsNull(false);
        return $item;
    }
}
