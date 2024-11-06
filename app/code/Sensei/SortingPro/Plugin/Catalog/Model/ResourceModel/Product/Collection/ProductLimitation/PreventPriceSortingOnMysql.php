<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Plugin\Catalog\Model\ResourceModel\Product\Collection\ProductLimitation;

use Sensei\SortingPro\Model\Elasticsearch\ApplierFlag;
use Magento\Catalog\Model\ResourceModel\Product\Collection\ProductLimitation;

class PreventPriceSortingOnMysql
{
    private $applierFlag;

    public function __construct(ApplierFlag $applierFlag)
    {
        $this->applierFlag = $applierFlag;
    }

    public function afterIsUsingPriceIndex(ProductLimitation $subject, bool $result): bool
    {
        if ($this->applierFlag->get()) {
            $result = false;
        }

        return $result;
    }
}
