<?php

namespace Uzer\Sales\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Sales\Api\Data\OrderRestoreInterface;
use Uzer\Sales\Model\ResourceModel\OrderRestore as ResourceModel;

class OrderRestore extends AbstractModel implements OrderRestoreInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sales_order_returns_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
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
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * Getter for Email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Setter for Email.
     *
     * @param string|null $email
     *
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->setData(self::EMAIL, $email);
    }

    /**
     * Getter for Phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * Setter for Phone.
     *
     * @param string|null $phone
     *
     * @return void
     */
    public function setPhone(?string $phone): void
    {
        $this->setData(self::PHONE, $phone);
    }

    /**
     * Getter for Description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Setter for Description.
     *
     * @param string|null $description
     *
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Getter for Image.
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * Setter for Image.
     *
     * @param string|null $image
     *
     * @return void
     */
    public function setImage(?string $image): void
    {
        $this->setData(self::IMAGE, $image);
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
}
