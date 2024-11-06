<?php

namespace Uzer\Checkoutstep\Model;

class PriceCurrency extends \Magento\Directory\Model\PriceCurrency
{

    public function format(
        $amount,
        $includeContainer = true,
        $precision = self::DEFAULT_PRECISION,
        $scope = null,
        $currency = null
    ): string
    {
        $data['precision'] = $precision;
        return $this->getCurrency($scope, $currency)
            ->formatPrecision($amount, $precision, $data, $includeContainer);
    }


}
