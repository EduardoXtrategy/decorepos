<?php

namespace Uzer\AdvancedFilter\Model\Data;

use Magento\Framework\DataObject;
use Uzer\AdvancedFilter\Api\Data\ProductTypeSizesInterface;

class ProductTypeSizes extends DataObject implements ProductTypeSizesInterface
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
    public function getSizeId(): ?int
    {
        return $this->getData(self::SIZE_ID) === null ? null
            : (int)$this->getData(self::SIZE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSizeId(?int $sizeId): void
    {
        $this->setData(self::SIZE_ID, $sizeId);
    }

    /**
     * @inheritDoc
     */
    public function getProductTypeId(): ?int
    {
        return $this->getData(self::PRODUCT_TYPE_ID) === null ? null
            : (int)$this->getData(self::PRODUCT_TYPE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductTypeId(?int $productTypeId): void
    {
        $this->setData(self::PRODUCT_TYPE_ID, $productTypeId);
    }

    /**
     * @inheritDoc
     */
    public function getSku(): ?string
    {
        return $this->getData(self::SKU) === null ? null
            : (string)$this->getData(self::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku(?string $sku): void
    {
        $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): bool
    {
        return !($this->getData(self::STATUS) === null) && $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(bool $status): void
    {
        $this->setData(self::STATUS, $status);
    }
}
