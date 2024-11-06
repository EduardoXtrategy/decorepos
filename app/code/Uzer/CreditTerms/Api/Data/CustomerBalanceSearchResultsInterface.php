<?php

namespace Uzer\CreditTerms\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * CustomerBalance entity search result.
 */
interface CustomerBalanceSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Set items.
     *
     * @param \Uzer\CreditTerms\Api\Data\CustomerBalanceInterface[] $items
     *
     * @return CustomerBalanceSearchResultsInterface
     */
    public function setItems(array $items): CustomerBalanceSearchResultsInterface;

    /**
     * Get items.
     *
     * @return \Uzer\CreditTerms\Api\Data\CustomerBalanceInterface[]
     */
    public function getItems(): array;
}
