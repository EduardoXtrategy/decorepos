<?php

namespace Uzer\CreditTerms\Model\ResourceModel\CustomerBalance;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\CreditTerms\Model\CustomerBalance;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance as CustomerBalanceAlias;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_balance';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(CustomerBalance::class, CustomerBalanceAlias::class);
    }
}
