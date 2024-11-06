<?php

namespace Uzer\CreditTerms\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterface;

/**
 * Save CustomerBalance Command.
 *
 * @api
 */
interface SaveCustomerBalanceInterface
{
    /**
     * Save CustomerBalance.
     * @param \Uzer\CreditTerms\Api\Data\CustomerBalanceInterface $customerBalance
     * @return CustomerBalanceInterface
     * @throws CouldNotSaveException
     */
    public function execute(CustomerBalanceInterface $customerBalance): CustomerBalanceInterface;
}
