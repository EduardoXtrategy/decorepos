<?php

namespace Uzer\CustomProducts\Api;

use Uzer\CustomProducts\Api\Data\CustomProductsInterface;

interface CustomProductsRepositoryInterface
{

    /**
     * @param CustomProductsInterface $items
     * @return CustomProductsInterface
     * @Api
     */
    public function saveByCustomerId(CustomProductsInterface $items): CustomProductsInterface;


    /**
     * @param CustomProductsInterface $items
     * @return CustomProductsInterface
     * @Api
     */
    public function deleteByCustomerId(CustomProductsInterface $items): CustomProductsInterface;

}
