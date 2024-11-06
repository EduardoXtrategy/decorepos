<?php

namespace Uzer\Core\Api;

use Magento\Sales\Model\Order;

interface OrderIntegrationInterface
{

    public function save(Order $order);

}
