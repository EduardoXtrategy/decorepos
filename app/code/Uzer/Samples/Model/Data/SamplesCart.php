<?php

namespace Uzer\Samples\Model\Data;

use Magento\Framework\DataObject;
use Uzer\Samples\Api\Data\SamplesCartInterface;

class SamplesCart extends DataObject implements SamplesCartInterface
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
    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID) === null ? null
            : (int)$this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(?int $customerId): void
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getActive(): ?bool
    {
        return $this->getData(self::ACTIVE) === null ? null
            : (bool)$this->getData(self::ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setActive(?bool $active): void
    {
        $this->setData(self::ACTIVE, $active);
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

    /**
     * @inheritDoc
     */
    public function getWebsiteId(): ?int
    {
        return $this->getData(self::WEBSITE_ID) === null ? null
            : (int)$this->getData(self::WEBSITE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setWebsiteId(?int $websiteId): void
    {
        $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * @inheritDoc
     */
    public function getCompleteAt(): ?string
    {
        return $this->getData(self::COMPLETE_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCompleteAt(?string $completeAt): void
    {
        $this->setData(self::COMPLETE_AT, $completeAt);
    }

    public function getCustomerAddressId(): ?int
    {
        return $this->getData(self::CUSTOMER_ADDRESS_ID);
    }

    public function setCustomerAddressId(int $customerAddressId): void
    {
        $this->setData(self::CUSTOMER_ADDRESS_ID, $customerAddressId);
    }
}
