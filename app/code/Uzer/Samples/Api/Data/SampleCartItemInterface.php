<?php

namespace Uzer\Samples\Api\Data;

interface SampleCartItemInterface
{
    /**
     * String constants for property names
     */
    const SAMPLES_CART_ID = "samples_cart_id";
    const SKU = "sku";
    const NAME = 'name';
    const IS_PARENT = "is_parent";
    const PARENT = "parent";
    const ATTRIBUTES = "attributes";
    const QTY = "qty";
    const PRODUCT_ID = "product_id";

    /**
     * Getter for SamplesCartId.
     *
     * @return int|null
     */
    public function getSamplesCartId(): ?int;

    /**
     * Setter for SamplesCartId.
     *
     * @param int|null $samplesCartId
     *
     * @return void
     */
    public function setSamplesCartId(?int $samplesCartId): void;

    /**
     * Getter for Sku.
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * Setter for Sku.
     *
     * @param string|null $sku
     *
     * @return void
     */
    public function setSku(?string $sku): void;

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void;

    /**
     * Getter for IsParent.
     *
     * @return bool|null
     */
    public function getIsParent(): ?bool;

    /**
     * Setter for IsParent.
     *
     * @param bool|null $isParent
     *
     * @return void
     */
    public function setIsParent(?bool $isParent): void;

    /**
     * Getter for Parent.
     *
     * @return string|null
     */
    public function getParent(): ?string;

    /**
     * Setter for Parent.
     *
     * @param string|null $parent
     *
     * @return void
     */
    public function setParent(?string $parent): void;

    /**
     * Getter for Attributes.
     *
     * @return string|null
     */
    public function getAttributes(): ?string;

    /**
     * Setter for Attributes.
     *
     * @param string|null $attributes
     *
     * @return void
     */
    public function setAttributes(?string $attributes): void;

    /**
     * Getter for Qty.
     *
     * @return int|null
     */
    public function getQty(): ?int;

    /**
     * Setter for Qty.
     *
     * @param int|null $qty
     *
     * @return void
     */
    public function setQty(?int $qty): void;

    /**
     * Getter for ProductId.
     *
     * @return int|null
     */
    public function getProductId(): ?int;

    /**
     * Setter for ProductId.
     *
     * @param int|null $productId
     *
     * @return void
     */
    public function setProductId(?int $productId): void;
}
