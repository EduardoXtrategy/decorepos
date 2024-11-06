<?php

namespace Uzer\Search\Api\Data;

interface ProductBannerInterface
{
    /**
     * String constants for property names
     */
    const ENTITY_ID = "entity_id";
    const ATTRIBUTE_NAME = 'attribute_name';
    const ATTRIBUTE_ID = "attribute_id";
    const CONTENT = "content";
    const STORE_ID = "store_id";


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
     * Getter for AttributeId.
     *
     * @return string|null
     */
    public function getAttributeName(): ?string;

    /**
     * Setter for AttributeId.
     *
     * @param string|null $attributeName
     *
     * @return void
     */
    public function setAttributeName(?string $attributeName): void;

    /**
     * Getter for AttributeId.
     *
     * @return int|null
     */
    public function getAttributeId(): ?int;

    /**
     * Setter for AttributeId.
     *
     * @param int|null $attributeId
     *
     * @return void
     */
    public function setAttributeId(?int $attributeId): void;

    /**
     * Getter for Banner.
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Setter for Banner.
     *
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void;

    /**
     * Getter for StoresId.
     *
     * @return int|null
     */
    public function getStoreId(): ?int;

    /**
     * Setter for StoresId.
     *
     * @param int|null $storeId
     *
     * @return void
     */
    public function setStoreId(?int $storeId): void;
}
