<?php

namespace Uzer\Infor\Api\Data;

interface ModelItemInterface
{
    /**
     * String constants for property names
     */
    public const IS_NULL = "IsNull";
    public const MODIFIED = "Modified";
    public const NAME = "Name";
    public const VALUE = "Value";

    /**
     * Getter for IsNull.
     *
     * @return bool|null
     */
    public function getIsNull(): ?bool;

    /**
     * Setter for IsNull.
     *
     * @param bool|null $isNull
     *
     * @return void
     */
    public function setIsNull(?bool $isNull): void;

    /**
     * Getter for Modified.
     *
     * @return bool|null
     */
    public function getModified(): ?bool;

    /**
     * Setter for Modified.
     *
     * @param bool|null $modified
     *
     * @return void
     */
    public function setModified(?bool $modified): void;

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
     * Getter for Value.
     *
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * Setter for Value.
     *
     * @param string|null $value
     *
     * @return void
     */
    public function setValue(?string $value): void;
}
