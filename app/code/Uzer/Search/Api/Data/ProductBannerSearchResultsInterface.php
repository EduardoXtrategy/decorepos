<?php

namespace Uzer\Search\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * ProductBanner entity search result.
 */
interface ProductBannerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Set items.
     *
     * @param \Uzer\Search\Api\Data\ProductBannerInterface[] $items
     *
     * @return ProductBannerSearchResultsInterface
     */
    public function setItems(array $items): ProductBannerSearchResultsInterface;

    /**
     * Get items.
     *
     * @return \Uzer\Search\Api\Data\ProductBannerInterface[]
     */
    public function getItems(): array;
}
