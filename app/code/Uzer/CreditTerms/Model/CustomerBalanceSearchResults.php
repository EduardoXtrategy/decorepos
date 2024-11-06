<?php

namespace Uzer\CreditTerms\Model;

use Magento\Framework\Api\SearchResults;
use Uzer\CreditTerms\Api\Data\CustomerBalanceSearchResultsInterface;

/**
 * CustomerBalance entity search results implementation.
 */
class CustomerBalanceSearchResults extends SearchResults implements CustomerBalanceSearchResultsInterface
{
    /**
     * Set items list.
     *
     * @param array $items
     *
     * @return CustomerBalanceSearchResultsInterface
     */
    public function setItems(array $items): CustomerBalanceSearchResultsInterface
    {
        return parent::setItems($items);
    }

    /**
     * Get items list.
     *
     * @return array
     */
    public function getItems(): array
    {
        return parent::getItems();
    }
}
