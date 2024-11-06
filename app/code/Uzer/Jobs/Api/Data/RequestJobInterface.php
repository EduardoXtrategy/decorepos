<?php

namespace Uzer\Jobs\Api\Data;

interface RequestJobInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const FULL_NAME = "full_name";
    const JOB_TITLE = "job_title";
    const EMAIL = "email";
    const PHONE = "phone";
    const MESSAGE = "message";
    const DOCUMENT = "document";
    const IP = 'ip';
    const USER_AGENT = 'user_agent';
    const STORE_ID = 'store_id';

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
    public function setEntityId($entityId);

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
     * Getter for JobTitle.
     *
     * @return string|null
     */
    public function getJobTitle(): ?string;

    /**
     * Setter for JobTitle.
     *
     * @param string|null $jobTitle
     *
     * @return void
     */
    public function setJobTitle(?string $jobTitle): void;

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
     * Getter for Document.
     *
     * @return string|null
     */
    public function getDocument(): ?string;

    /**
     * Setter for Document.
     *
     * @param string|null $document
     *
     * @return void
     */
    public function setDocument(?string $document): void;

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
    public function setIp(?string $ip);

    /**
     * Getter for User Agent.
     *
     * @return string|null
     */
    public function getUserAgent(): ?string;

    /**
     * Setter for User agent.
     *
     * @param string|null $userAgent
     *
     * @return void
     */
    public function setUserAgent(?string $userAgent);

    /**
     * @return int|null
     */
    public function getStoreId(): ?int;

    /**
     * @param int $storeId
     * @return void
     */
    public function setStoreId(int $storeId);
}
