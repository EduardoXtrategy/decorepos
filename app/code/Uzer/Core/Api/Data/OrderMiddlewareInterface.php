<?php

namespace Uzer\Core\Api\Data;

interface OrderMiddlewareInterface
{
    /**
     * String constants for property names
     */
    public const ORDER_ID = "order_id";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";
    public const SEND = "send";
    public const QTY = 'qty';

    /**
     * Getter for OrderId.
     *
     * @return int|null
     */
    public function getOrderId(): ?int;

    /**
     * Setter for OrderId.
     *
     * @param int|null $orderId
     *
     * @return void
     */
    public function setOrderId(?int $orderId): void;

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;

    /**
     * Getter for Send.
     *
     * @return bool|null
     */
    public function getSend(): ?bool;

    /**
     * Setter for Send.
     *
     * @param bool|null $send
     *
     * @return void
     */
    public function setSend(?bool $send): void;

    /**
     * @return int|null
     */
    public function getQty(): ?int;

    /**
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty): void;
}
