<?php

namespace Uzer\CustomProducts\Model\Data;

use Uzer\CustomProducts\Api\Data\CustomProductsInterface;

class CustomProducts implements CustomProductsInterface
{

    private int $customerId;
    private array $skus;


    public function setCustomerId(int $customerId)
    {
        $this->customerId = $customerId;
    }

    public function setSkus(array $skus)
    {
        $this->skus = $skus;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getSkus(): array
    {
        return $this->skus;
    }
}
