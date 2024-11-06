<?php

namespace Uzer\Middleware\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Core\Api\Data\OrderMiddlewareInterface;
use Uzer\Middleware\Model\ResourceModel\OrderMiddleware as ResourceModel;

class OrderMiddleware extends AbstractModel implements OrderMiddlewareInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sale_orders_middleware_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Getter for OrderId.
     *
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->getData(self::ORDER_ID) === null ? null
            : (int)$this->getData(self::ORDER_ID);
    }

    /**
     * Setter for OrderId.
     *
     * @param int|null $orderId
     *
     * @return void
     */
    public function setOrderId(?int $orderId): void
    {
        $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Getter for Send.
     *
     * @return bool|null
     */
    public function getSend(): ?bool
    {
        return $this->getData(self::SEND) === null ? null
            : (bool)$this->getData(self::SEND);
    }

    /**
     * Setter for Send.
     *
     * @param bool|null $send
     *
     * @return void
     */
    public function setSend(?bool $send): void
    {
        $this->setData(self::SEND, $send);
    }

    public function getQty(): ?int
    {
        return $this->getData(self::QTY) === null ? 0 : (int)$this->getData(self::QTY);
    }

    public function setQty(int $qty): void
    {
        $this->setData(self::QTY, $qty);
    }
}
