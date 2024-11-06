<?php

namespace Uzer\Checkoutstep\Plugin\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order;
use Uzer\Checkoutstep\Model\CustomFormat;

class OrderWrapper
{
    protected CustomFormat $customFormat;

    /**
     * @param CustomFormat $customFormat
     */
    public function __construct(CustomFormat $customFormat)
    {
        $this->customFormat = $customFormat;
    }

    /**
     * @param Order $subject
     * @param $result
     * @param $amount
     * @param bool $includeContainer
     * @return string
     * @throws NoSuchEntityException
     */
    public function afterFormatPrice(Order $subject, $result, $amount, $includeContainer = true): string
    {
        return $this->customFormat->format($amount);
    }

}
