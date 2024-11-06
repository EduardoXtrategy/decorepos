<?php

namespace Uzer\Infor\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Infor\Api\Data\CustomerAddressControlInterface;
use Uzer\Infor\Model\ResourceModel\CustomerAddressControl as ResourceModel;

class CustomerAddressControl extends AbstractModel implements CustomerAddressControlInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_address_control_model';

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
     * Getter for AddressId.
     *
     * @return int|null
     */
    public function getAddressId(): ?int
    {
        return $this->getData(self::ADDRESS_ID) === null ? null
            : (int)$this->getData(self::ADDRESS_ID);
    }

    /**
     * Setter for AddressId.
     *
     * @param int|null $addressId
     *
     * @return void
     */
    public function setAddressId(?int $addressId): void
    {
        $this->setData(self::ADDRESS_ID, $addressId);
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
