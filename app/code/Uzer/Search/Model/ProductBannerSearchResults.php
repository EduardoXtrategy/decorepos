<?php

namespace Uzer\Search\Model;

use Magento\Framework\Api\SearchResults;
use Uzer\Search\Api\Data\ProductBannerSearchResultsInterface;

/**
 * ProductBanner entity search results implementation.
 */
class ProductBannerSearchResults extends SearchResults implements ProductBannerSearchResultsInterface
{
    /**
     * @inheritDoc
     */
    public function setItems(array $items): ProductBannerSearchResultsInterface
    {
        return parent::setItems($items);
    }

    /**
     * @inheritDoc
     */
    public function getItems(): array
    {
        return parent::getItems();
    }
}
