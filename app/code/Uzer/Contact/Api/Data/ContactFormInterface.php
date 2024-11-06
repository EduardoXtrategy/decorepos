<?php

namespace Uzer\Contact\Api\Data;

interface ContactFormInterface
{
    /**
     * String constants for property names
     */
    const FULL_NAME = "full_name";
    const COMPANY = "company";
    const EMAIL = "email";
    const PHONE = "phone";
    const MESSAGE = "message";
    const IP = "ip";
    const USER_AGENT = "user_agent";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const STORE_ID = "store_id";

    /**
     * Getter for FullName.
     *
     * @return string|null
     */
    public function getFullName(): ?string;

    /**
     * Setter for FullName.
     *
     * @param string|null $fullName
     *
     * @return void
     */
    public function setFullName(?string $fullName): void;

    /**
     * Getter for Company.
     *
     * @return string|null
     */
    public function getCompany(): ?string;

    /**
     * Setter for Company.
     *
     * @param string|null $company
     *
     * @return void
     */
    public function setCompany(?string $company): void;

    /**
     * Getter for Email.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Setter for Email.
     *
     * @param string|null $email
     *
     * @return void
     */
    public function setEmail(?string $email): void;

    /**
     * Getter for Phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * Setter for Phone.
     *
     * @param string|null $phone
     *
     * @return void
     */
    public function setPhone(?string $phone): void;

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
     * Getter for Ip.
     *
     * @return string|null
     */
    public function getIp(): ?string;

    /**
     * Setter for Ip.
     *
     * @param string|null $ip
     *
     * @return void
     */
    public function setIp(?string $ip): void;

    /**
     * Getter for UserAgent.
     *
     * @return string|null
     */
    public function getUserAgent(): ?string;

    /**
     * Setter for UserAgent.
     *
     * @param string|null $userAgent
     *
     * @return void
     */
    public function setUserAgent(?string $userAgent): void;

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
