<?php

namespace Uzer\CreditTerms\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterface;

class CustomerBalance extends AbstractModel implements CustomerBalanceInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_balance';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Uzer\CreditTerms\Model\ResourceModel\CustomerBalance::class);
    }

    /**
     * Getter for Customersid.
     *
     * @return int|null
     */
    public function getCustomersId(): ?int
    {
        return $this->getData(self::CUSTOMERS_ID) === null ? null
            : (int)$this->getData(self::CUSTOMERS_ID);
    }

    /**
     * Setter for Customersid.
     *
     * @param int|null $customersId
     *
     * @return void
     */
    public function setCustomersId(?int $customersId): void
    {
        $this->setData(self::CUSTOMERS_ID, $customersId);
    }

    /**
     * Getter for Value.
     *
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->getData(self::VALUE) === null ? null
            : (float)$this->getData(self::VALUE);
    }

    /**
     * Setter for Value.
     *
     * @param float|null $value
     *
     * @return void
     */
    public function setValue(?float $value): void
    {
        $this->setData(self::VALUE, $value);
    }

    /**
     * Getter for Createdat.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Setter for Createdat.
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
     * Getter for Updatedat.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Setter for Updatedat.
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
