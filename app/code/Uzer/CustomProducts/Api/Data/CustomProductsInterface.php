<?php

namespace Uzer\CustomProducts\Api\Data;

interface CustomProductsInterface
{

    /**
     * @param int $customerId
     * @return void
     */
    public function setCustomerId(int $customerId);

    /**
     * @param array $skus
     * @return void
     */
    public function setSkus(array $skus);


    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @return string[]
     */
    public function getSkus(): array;

}
