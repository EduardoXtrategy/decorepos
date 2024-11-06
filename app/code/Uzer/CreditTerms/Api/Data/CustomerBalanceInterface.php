<?php

namespace Uzer\CreditTerms\Api\Data;

interface CustomerBalanceInterface
{
    public const ENTITY_ID = "entity_id";
    public const CUSTOMERS_ID = "customers_id";
    public const VALUE = "value";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";

    /**
     * Getter for Customersid.
     *
     * @return int|null
     */
    public function getCustomersId(): ?int;

    /**
     * Setter for Customersid.
     *
     * @param int|null $customersId
     *
     * @return void
     */
    public function setCustomersId(?int $customersId): void;

    /**
     * Getter for Value.
     *
     * @return float|null
     */
    public function getValue(): ?float;

    /**
     * Setter for Value.
     *
     * @param float|null $value
     *
     * @return void
     */
    public function setValue(?float $value): void;

    /**
     * Getter for Createdat.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for Createdat.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * Getter for Updatedat.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Setter for Updatedat.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;
}
