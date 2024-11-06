<?php

namespace Uzer\CustomProducts\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Uzer\CustomProducts\Api\Data\CategoryCustomerInterface;

class CategoryCustomer extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'category_customers_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('category_customers', CategoryCustomerInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }
}
