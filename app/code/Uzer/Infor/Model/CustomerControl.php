<?php

namespace Uzer\Infor\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Infor\Api\Data\CustomerControlInterface;
use Uzer\Infor\Model\ResourceModel\CustomerControl as ResourceModel;

class CustomerControl extends AbstractModel implements CustomerControlInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_control_model';

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
     * Getter for CustomerId.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID) === null ? null
            : (int)$this->getData(self::CUSTOMER_ID);
    }

    /**
     * Setter for CustomerId.
     *
     * @param int|null $customerId
     *
     * @return void
     */
    public function setCustomerId(?int $customerId): void
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Getter for Saved.
     *
     * @return bool|null
     */
    public function getSaved(): ?bool
    {
        return $this->getData(self::SAVED) === null ? null
            : (bool)$this->getData(self::SAVED);
    }

    /**
     * Setter for Saved.
     *
     * @param bool|null $saved
     *
     * @return void
     */
    public function setSaved(?bool $saved): void
    {
        $this->setData(self::SAVED, $saved);
    }

    /**
     * Getter for Attempts.
     *
     * @return int|null
     */
    public function getAttempts(): ?int
    {
        return $this->getData(self::ATTEMPTS) === null ? null
            : (int)$this->getData(self::ATTEMPTS);
    }

    /**
     * Setter for Attempts.
     *
     * @param int|null $attempts
     *
     * @return void
     */
    public function setAttempts(?int $attempts): void
    {
        $this->setData(self::ATTEMPTS, $attempts);
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
