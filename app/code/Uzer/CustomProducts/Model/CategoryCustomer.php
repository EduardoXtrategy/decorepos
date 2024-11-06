<?php

namespace Uzer\CustomProducts\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\CustomProducts\Api\Data\CategoryCustomerInterface;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomer as ResourceModel;

class CategoryCustomer extends AbstractModel implements CategoryCustomerInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'category_customers';

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
     * Getter for CategoryId.
     *
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->getData(self::CATEGORY_ID) === null ? null
            : (int)$this->getData(self::CATEGORY_ID);
    }

    /**
     * Setter for CategoryId.
     *
     * @param int|null $categoryId
     *
     * @return void
     */
    public function setCategoryId(?int $categoryId): void
    {
        $this->setData(self::CATEGORY_ID, $categoryId);
    }
}
