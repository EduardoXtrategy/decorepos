<?php

namespace Uzer\Infor\Api\Data;

interface CustomerAddressControlInterface
{
    /**
     * String constants for property names
     */
    public const ADDRESS_ID = "address_id";
    public const TYPE = "type";
    public const SAVED = "saved";
    public const ATTEMPTS = "attempts";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";


    public function getEntityId();

    public function setEntityId($entityId);


    /**
     * Getter for Type.
     *
     * @return string|null
     */
    public function getType(): string;


    /**
     * Setter for Type.
     *
     * @param string $type
     *
     * @return void
     */
    public function setType(string $type): void;

    /**
     * Getter for AddressId.
     *
     * @return int|null
     */
    public function getAddressId(): ?int;

    /**
     * Setter for AddressId.
     *
     * @param int|null $addressId
     *
     * @return void
     */
    public function setAddressId(?int $addressId): void;

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
