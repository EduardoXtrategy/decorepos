<?php

namespace Uzer\Checkoutstep\Plugin\Order;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Uzer\Customer\Model\CustomerCustomInfo;

class OrderSenderWrapper
{


    public function beforeSend(OrderSender $subject, Order $order, $forceSyncMode = false)
    {
    }
}
