<?php

namespace Uzer\Samples\Api\Data;

interface SampleKitInterface
{
    /**
     * String constants for property names
     */
    const ADDRESS_NAME = "address_name";
    const STREET_ADDRESS = "street_address";
    const COUNTRY = "country";
    const STATE = "state";
    const CITE = "cite";
    const ZIP_CODE = "zip_code";
    const MESSAGE = "message";
    const COMPLETE_AT = "complete_at";

    /**
     * Getter for AddressName.
     *
     * @return string|null
     */
    public function getAddressName(): ?string;

    /**
     * Setter for AddressName.
     *
     * @param string|null $addressName
     *
     * @return void
     */
    public function setAddressName(?string $addressName): void;

    /**
     * Getter for StreetAddress.
     *
     * @return string|null
     */
    public function getStreetAddress(): ?string;

    /**
     * Setter for StreetAddress.
     *
     * @param string|null $streetAddress
     *
     * @return void
     */
    public function setStreetAddress(?string $streetAddress): void;

    /**
     * Getter for Country.
     *
     * @return string|null
     */
    public function getCountry(): ?string;

    /**
     * Setter for Country.
     *
     * @param string|null $country
     *
     * @return void
     */
    public function setCountry(?string $country): void;

    /**
     * Getter for State.
     *
     * @return string|null
     */
    public function getState(): ?string;

    /**
     * Setter for State.
     *
     * @param string|null $state
     *
     * @return void
     */
    public function setState(?string $state): void;

    /**
     * Getter for Cite.
     *
     * @return string|null
     */
    public function getCite(): ?string;

    /**
     * Setter for Cite.
     *
     * @param string|null $cite
     *
     * @return void
     */
    public function setCite(?string $cite): void;

    /**
     * Getter for ZipCode.
     *
     * @return string|null
     */
    public function getZipCode(): ?string;

    /**
     * Setter for ZipCode.
     *
     * @param string|null $zipCode
     *
     * @return void
     */
    public function setZipCode(?string $zipCode): void;

    /**
     * Getter for Message.
     *
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * Setter for Message.
     *
     * @param string|null $message
     *
     * @return void
     */
    public function setMessage(?string $message): void;

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
}
