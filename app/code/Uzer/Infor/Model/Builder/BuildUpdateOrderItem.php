<?php

namespace Uzer\Infor\Model\Builder;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;
use Uzer\Infor\Api\Data\RequestModelInterface;
use Uzer\Infor\Api\Data\RequestModelInterfaceFactory;
use Uzer\Infor\Api\Data\ModelItemInterfaceFactory;
use Uzer\Infor\Api\Data\ModelItemInterface;

class BuildUpdateOrderItem
{

    protected RequestModelInterfaceFactory $customerModelFactory;
    protected ModelItemInterfaceFactory $customerItemFactory;
    protected Order $order;

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
    public function build(Order $order, string $lineId, string $coNum, string $stat): RequestModelInterface
    {
        $this->order = $order;
        $model = $this->customerModelFactory->create();
        $model->setAction(2);
        $model->setItemId($lineId);
        $model->appendProperty($this->buildCoNum($coNum));
        $model->appendProperty($this->buildStat($stat));
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

    public function buildStat(string $stat): ModelItemInterface
    {
        $item = $this->customerItemFactory->create();
        $item->setName('Stat');
        $item->setValue($stat);
        $item->setModified(true);
        $item->setIsNull(false);
        return $item;
    }
}
