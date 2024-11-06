<?php

namespace Uzer\Contact\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Contact\Api\Data\ContactFormInterface;

class ContactForm extends AbstractModel implements ContactFormInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'contact_form_model';

    protected function _construct()
    {
        $this->_init(\Uzer\Contact\Model\ResourceModel\ContactForm::class);
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
    public function getCompany(): ?string
    {
        return $this->getData(self::COMPANY);
    }

    /**
     * @inheritDoc
     */
    public function setCompany(?string $company): void
    {
        $this->setData(self::COMPANY, $company);
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
    public function getIp(): ?string
    {
        return $this->getData(self::IP);
    }

    /**
     * @inheritDoc
     */
    public function setIp(?string $ip): void
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
    public function setUserAgent(?string $userAgent): void
    {
        $this->setData(self::USER_AGENT, $userAgent);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): ?int
    {
        return $this->getData(self::STORE_ID) === null ? null
            : (int)$this->getData(self::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(?int $storeId): void
    {
        $this->setData(self::STORE_ID, $storeId);
    }
}
