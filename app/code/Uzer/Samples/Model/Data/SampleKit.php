<?php

namespace Uzer\Samples\Model\Data;

use Magento\Framework\DataObject;
use Uzer\Samples\Api\Data\SampleKitInterface;

class SampleKit extends DataObject implements SampleKitInterface
{
    /**
     * @inheritDoc
     */
    public function getAddressName(): ?string
    {
        return $this->getData(self::ADDRESS_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setAddressName(?string $addressName): void
    {
        $this->setData(self::ADDRESS_NAME, $addressName);
    }

    /**
     * @inheritDoc
     */
    public function getStreetAddress(): ?string
    {
        return $this->getData(self::STREET_ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setStreetAddress(?string $streetAddress): void
    {
        $this->setData(self::STREET_ADDRESS, $streetAddress);
    }

    /**
     * @inheritDoc
     */
    public function getCountry(): ?string
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * @inheritDoc
     */
    public function setCountry(?string $country): void
    {
        $this->setData(self::COUNTRY, $country);
    }

    /**
     * @inheritDoc
     */
    public function getState(): ?string
    {
        return $this->getData(self::STATE);
    }

    /**
     * @inheritDoc
     */
    public function setState(?string $state): void
    {
        $this->setData(self::STATE, $state);
    }

    /**
     * @inheritDoc
     */
    public function getCite(): ?string
    {
        return $this->getData(self::CITE);
    }

    /**
     * @inheritDoc
     */
    public function setCite(?string $cite): void
    {
        $this->setData(self::CITE, $cite);
    }

    /**
     * @inheritDoc
     */
    public function getZipCode(): ?string
    {
        return $this->getData(self::ZIP_CODE);
    }

    /**
     * @inheritDoc
     */
    public function setZipCode(?string $zipCode): void
    {
        $this->setData(self::ZIP_CODE, $zipCode);
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
}
