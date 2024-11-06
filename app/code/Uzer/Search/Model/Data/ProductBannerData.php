<?php

namespace Uzer\Search\Model\Data;

use Magento\Framework\DataObject;
use Uzer\Search\Api\Data\ProductBannerInterface;

class ProductBannerData extends DataObject implements ProductBannerInterface
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
    public function getAttributeId(): ?int
    {
        return $this->getData(self::ATTRIBUTE_ID) === null ? null
            : (int)$this->getData(self::ATTRIBUTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setAttributeId(?int $attributeId): void
    {
        $this->setData(self::ATTRIBUTE_ID, $attributeId);
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

    public function getContent(): ?string
    {
        return $this->getData(self::STORE_ID);
    }

    public function setContent(?string $content): void
    {
        $this->setData(self::CONTENT, $content);
    }

    public function getAttributeName(): ?string
    {
        return $this->getData(self::ATTRIBUTE_NAME);
    }

    public function setAttributeName(?string $attributeName): void
    {
        $this->setData(self::ATTRIBUTE_NAME, $attributeName);
    }
}
