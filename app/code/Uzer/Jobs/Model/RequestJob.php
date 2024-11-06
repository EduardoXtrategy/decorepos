<?php

namespace Uzer\Jobs\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Jobs\Api\Data\RequestJobInterface;

class RequestJob extends AbstractModel implements RequestJobInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_request_jobs_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(\Uzer\Jobs\Model\ResourceModel\RequestJob::class);
    }

    /**
     * @inheritDoc
     */
    public function getFullName(): ?string
    {
        return $this->getData(self::FULL_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setFullName(?string $fullName): void
    {
        $this->setData(self::FULL_NAME, $fullName);
    }

    /**
     * @inheritDoc
     */
    public function getJobTitle(): ?string
    {
        return $this->getData(self::JOB_TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setJobTitle(?string $jobTitle): void
    {
        $this->setData(self::JOB_TITLE, $jobTitle);
    }

    /**
     * @inheritDoc
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setEmail(?string $email): void
    {
        $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritDoc
     */
    public function getPhone(): ?string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * @inheritDoc
     */
    public function setPhone(?string $phone): void
    {
        $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?string
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(?string $message): void
    {
        $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function getDocument(): ?string
    {
        return $this->getData(self::DOCUMENT);
    }

    /**
     * @inheritDoc
     */
    public function setDocument(?string $document): void
    {
        $this->setData(self::DOCUMENT, $document);
    }

    /**
     * @inheritDoc
     */
    public function getIp(): ?string
    {
        return $this->getData(self::IP);
    }

    /**
     * @inheritDoc
     */
    public function setIp(?string $ip)
    {
        $this->setData(self::IP, $ip);
    }

    /**
     * @inheritDoc
     */
    public function getUserAgent(): ?string
    {
        return $this->getData(self::USER_AGENT);
    }

    /**
     * @inheritDoc
     */
    public function setUserAgent(?string $userAgent)
    {
        $this->setData(self::USER_AGENT, $userAgent);
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): ?int
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(int $storeId)
    {
        $this->setData(self::STORE_ID, $storeId);
    }
}
