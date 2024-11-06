<?php

namespace Uzer\CreditTerms\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterface;

class CustomerBalance extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_balance';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('customer_balance', CustomerBalanceInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }
}
