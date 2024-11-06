<?php

namespace Uzer\CreditTerms\Api;

use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete CustomerBalance by id Command.
 *
 * @api
 */
interface DeleteCustomerBalanceByIdInterface
{
    /**
     * Delete CustomerBalance.
     * @param int $entityId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void;
}
