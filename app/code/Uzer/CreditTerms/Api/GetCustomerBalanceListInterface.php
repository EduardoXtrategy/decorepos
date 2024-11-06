<?php

namespace Uzer\CreditTerms\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Uzer\CreditTerms\Api\Data\CustomerBalanceSearchResultsInterface;

/**
 * Get CustomerBalance list by search criteria query.
 *
 * @api
 */
interface GetCustomerBalanceListInterface
{
    /**
     * Get CustomerBalance list by search criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Uzer\CreditTerms\Api\Data\CustomerBalanceSearchResultsInterface
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): CustomerBalanceSearchResultsInterface;
}
