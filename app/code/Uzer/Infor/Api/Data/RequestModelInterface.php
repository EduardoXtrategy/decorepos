<?php

namespace Uzer\Infor\Api\Data;

interface RequestModelInterface
{
    /**
     * String constants for property names
     */
    public const ACTION = "action";
    public const ITEM_ID = 'ItemId';
    public const PROPERTIES = 'Properties';

    /**
     * Getter for Action.
     *
     * @return int|null
     */
    public function getAction(): ?int;

    /**
     * Setter for Action.
     *
     * @param int|null $action
     *
     * @return void
     */
    public function setAction(?int $action): void;

    /**
     * Getter for ItemId.
     *
     * @return string|null
     */
    public function getItemId(): ?string;

    /**
     * Setter for ItemId.
     *
     * @param string|null $itemId
     *
     * @return void
     */
    public function setItemId(?string $itemId): void;

    /**
     * Getter for Properties.
     *
     * @return \Uzer\Infor\Api\Data\ModelItemInterface[]|null
     */
    public function getProperties(): ?array;

    /**
     * Setter for Properties.
     *
     * @param \Uzer\Infor\Api\Data\ModelItemInterface[]|null $properties
     *
     * @return void
     */
    public function setProperties(?array $properties): void;

    /**
     *
     * Add a property to the list of properties
     *
     * @param ModelItemInterface $property
     * @return void
     */
    public function appendProperty(ModelItemInterface $property): void;
}
