<?php

namespace Uzer\CustomProducts\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProduct as ResourceModel;

class CustomerProduct extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_products';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }


    public function setCustomerId(int $customerId): CustomerProduct
    {
        $this->setData('customers_id', $customerId);
        return $this;
    }

    public function getCustomerId()
    {
        return $this->getData('customers_id');
    }

    public function setSku(string $sku): CustomerProduct
    {
        $this->setData('sku', $sku);
        return $this;
    }

    public function getSku()
    {
        return $this->getData('sku');
    }
}
