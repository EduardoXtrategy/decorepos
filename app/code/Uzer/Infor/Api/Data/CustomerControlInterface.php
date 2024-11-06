<?php

namespace Uzer\Infor\Api\Data;

interface CustomerControlInterface
{
    /**
     * String constants for property names
     */
    public const CUSTOMER_ID = "customer_id";
    public const SAVED = "saved";
    public const ATTEMPTS = "attempts";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";


    public function getEntityId();

    public function setEntityId($entityId);

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
     * Getter for Saved.
     *
     * @return bool|null
     */
    public function getSaved(): ?bool;

    /**
     * Setter for Saved.
     *
     * @param bool|null $saved
     *
     * @return void
     */
    public function setSaved(?bool $saved): void;

    /**
     * Getter for Attempts.
     *
     * @return int|null
     */
    public function getAttempts(): ?int;

    /**
     * Setter for Attempts.
     *
     * @param int|null $attempts
     *
     * @return void
     */
    public function setAttempts(?int $attempts): void;

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;
}
