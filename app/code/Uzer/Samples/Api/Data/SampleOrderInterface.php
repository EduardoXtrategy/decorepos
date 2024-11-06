<?php

namespace Uzer\Samples\Api\Data;

interface SampleOrderInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const SAMPLE_QUOTE_ID = "sample_quote_id";
    const DATE_PURCHASE = "date_purchase";
    const CUSTOMERS_ID = "customers_id";
    const NOTE = 'note';
    const CUSTOMER_ADDRESS_ID = 'customer_address_id';
    const FULL_NAME = 'full_name';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const STORE_ID = "store_id";

    /**
     * Retrieve entity id
     *
     * @return mixed
     */
    public function getEntityId();

    /**
     * Set entity id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId(?int $entityId);

    /**
     * Getter for SampleQuoteId.
     *
     * @return int|null
     */
    public function getSampleQuoteId(): ?int;

    /**
     * Setter for SampleQuoteId.
     *
     * @param int|null $sampleQuoteId
     *
     * @return void
     */
    public function setSampleQuoteId(?int $sampleQuoteId): void;

    /**
     * Getter for DatePurchase.
     *
     * @return string|null
     */
    public function getDatePurchase(): ?string;

    /**
     * Setter for DatePurchase.
     *
     * @param string|null $datePurchase
     *
     * @return void
     */
    public function setDatePurchase(?string $datePurchase): void;

    /**
     * Getter for CustomersId.
     *
     * @return int|null
     */
    public function getCustomersId(): ?int;

    /**
     * Setter for CustomersId.
     *
     * @param int|null $customersId
     *
     * @return void
     */
    public function setCustomersId(?int $customersId): void;

    /**
     * @return string
     */
    public function getNote(): ?string;

    /**
     * @param string $note
     * @return void
     */
    public function setNote(string $note): void;

    /**
     * @return int|null
     */
    public function getCustomerAddressId(): ?int;

    /**
     * @param int $customerAddressId
     * @return void
     */
    public function setCustomerAddressId(int $customerAddressId): void;

    /**
     * @return string|null
     */
    public function getFullName(): ?string;

    /**
     * @param string|null $fullname
     * @return void
     */
    public function setFullName(?string $fullname): void;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * @param string|null $firstname
     * @return void
     */
    public function setFirstName(?string $firstname): void;

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * @param string|null $lastname
     * @return void
     */
    public function setLastName(?string $lastname): void;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void;

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

}
