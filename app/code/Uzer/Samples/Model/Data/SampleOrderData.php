<?php

namespace Uzer\Samples\Model\Data;

use Magento\Framework\DataObject;
use Uzer\Samples\Api\Data\SampleOrderInterface;

class SampleOrderData extends DataObject implements SampleOrderInterface
{
    /**
     * @inheritDoc
     */
    public function getEntityId(): ?int
    {
        return $this->getData(self::ENTITY_ID) === null ? null
            : (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId(?int $entityId): void
    {
        $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getSampleQuoteId(): ?int
    {
        return $this->getData(self::SAMPLE_QUOTE_ID) === null ? null
            : (int)$this->getData(self::SAMPLE_QUOTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSampleQuoteId(?int $sampleQuoteId): void
    {
        $this->setData(self::SAMPLE_QUOTE_ID, $sampleQuoteId);
    }

    /**
     * @inheritDoc
     */
    public function getDatePurchase(): ?string
    {
        return $this->getData(self::DATE_PURCHASE);
    }

    /**
     * @inheritDoc
     */
    public function setDatePurchase(?string $datePurchase): void
    {
        $this->setData(self::DATE_PURCHASE, $datePurchase);
    }

    /**
     * @inheritDoc
     */
    public function getCustomersId(): ?int
    {
        return $this->getData(self::CUSTOMERS_ID) === null ? null
            : (int)$this->getData(self::CUSTOMERS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomersId(?int $customersId): void
    {
        $this->setData(self::CUSTOMERS_ID, $customersId);
    }

    /**
     * @inheritDoc
     */
    public function getNote(): ?string
    {
        return $this->getData(self::NOTE) === null ? null
            : (int)$this->getData(self::NOTE);
    }

    /**
     * @inheritDoc
     */
    public function setNote(string $note): void
    {
        $this->setData(self::NOTE, $note);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerAddressId(): ?int
    {
        return $this->getData(self::CUSTOMER_ADDRESS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerAddressId(int $customerAddressId): void
    {
        $this->setData(self::CUSTOMER_ADDRESS_ID, $customerAddressId);
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
    public function setFullName(?string $fullname): void
    {
        $this->setData(self::FULL_NAME, $fullname);
    }

    /**
     * @inheritDoc
     */
    public function getFirstName(): ?string
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setFirstName(?string $firstname): void
    {
        $this->setData(self::FIRST_NAME, $firstname);
    }

    /**
     * @inheritDoc
     */
    public function getLastName(): ?string
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setLastName(?string $lastname): void
    {
        $this->setData(self::LAST_NAME, $lastname);
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
