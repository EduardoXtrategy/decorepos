<?php

namespace Uzer\AdvancedFilter\Api\Data;

interface ProductTypeSizesInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const SIZE_ID = "size_id";
    const PRODUCT_TYPE_ID = "product_type_id";
    const SKU = "sku";
    const STATUS = "status";

    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return void
     */
    public function setEntityId(?int $entityId): void;

    /**
     * Getter for SizeId.
     *
     * @return int|null
     */
    public function getSizeId(): ?int;

    /**
     * Setter for SizeId.
     *
     * @param int|null $sizeId
     *
     * @return void
     */
    public function setSizeId(?int $sizeId): void;

    /**
     * Getter for ProductTypeId.
     *
     * @return int|null
     */
    public function getProductTypeId(): ?int;

    /**
     * Setter for ProductTypeId.
     *
     * @param int|null $productTypeId
     *
     * @return void
     */
    public function setProductTypeId(?int $productTypeId): void;

    /**
     * Getter for SKU.
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * Setter for SKU.
     *
     * @param string|null $sku
     *
     * @return void
     */
    public function setSku(?string $sku): void;

    /**
     * Getter for Status.
     *
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * Setter for Status.
     *
     * @param bool $status
     *
     * @return void
     */
    public function setStatus(bool $status): void;
}
