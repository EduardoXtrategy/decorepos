<?php

namespace Uzer\Samples\Api\Data;

interface SamplesCartInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const CUSTOMER_ID = "customer_id";
    const ACTIVE = "active";
    const STORE_ID = "store_id";
    const WEBSITE_ID = "website_id";
    const COMPLETE_AT = "complete_at";
    const CUSTOMER_ADDRESS_ID = 'customer_address_id';

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
     * Getter for Active.
     *
     * @return bool|null
     */
    public function getActive(): ?bool;

    /**
     * Setter for Active.
     *
     * @param bool|null $active
     *
     * @return void
     */
    public function setActive(?bool $active): void;

    /**
     * Getter for StoreId.
     *
     * @return int|null
     */
    public function getStoreId(): ?int;

    /**
     * Setter for StoreId.
     *
     * @param int|null $storeId
     *
     * @return void
     */
    public function setStoreId(?int $storeId): void;

    /**
     * Getter for WebsiteId.
     *
     * @return int|null
     */
    public function getWebsiteId(): ?int;

    /**
     * Setter for WebsiteId.
     *
     * @param int|null $websiteId
     *
     * @return void
     */
    public function setWebsiteId(?int $websiteId): void;

    /**
     * Getter for CompleteAt.
     *
     * @return string|null
     */
    public function getCompleteAt(): ?string;

    /**
     * Setter for CompleteAt.
     *
     * @param string|null $completeAt
     *
     * @return void
     */
    public function setCompleteAt(?string $completeAt): void;

    /**
     * @return int|null
     */
    public function getCustomerAddressId(): ?int;

    /**
     * @param int $customerAddressId
     * @return void
     */
    public function setCustomerAddressId(int $customerAddressId): void;
}
