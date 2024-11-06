<?php

namespace Uzer\Checkoutstep\Plugin\Order;

use Magento\Sales\Block\Order\Totals;
use Uzer\Checkoutstep\Model\CustomFormat;

class TotalsWrapper
{

    protected CustomFormat $customFormat;

    /**
     * @param CustomFormat $customFormat
     */
    public function __construct(CustomFormat $customFormat)
    {
        $this->customFormat = $customFormat;
    }


    public function afterFormatValue(Totals $subject, $result, $total): string
    {
        return $this->customFormat->format($total->getValue());
    }

}
