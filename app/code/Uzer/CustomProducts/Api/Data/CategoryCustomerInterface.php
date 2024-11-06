<?php

namespace Uzer\CustomProducts\Api\Data;

interface CategoryCustomerInterface
{
    /**
     * String constants for property names
     */
    public const ENTITY_ID = "entity_id";
    public const CUSTOMER_ID = "customer_id";
    public const CATEGORY_ID = "category_id";

    /**
     * Getter for CustomerId.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Setter for CustomerId.
     *
     * @param int|null $customerId
     *
     * @return void
     */
    public function setCustomerId(?int $customerId): void;

    /**
     * Getter for CategoryId.
     *
     * @return int|null
     */
    public function getCategoryId(): ?int;

    /**
     * Setter for CategoryId.
     *
     * @param int|null $categoryId
     *
     * @return void
     */
    public function setCategoryId(?int $categoryId): void;
}
