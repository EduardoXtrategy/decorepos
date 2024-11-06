<?php

namespace Uzer\Search\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Uzer\Search\Api\Data\ProductBannerSearchResultsInterface;

/**
 * Get ProductBanner list by search criteria query.
 *
 * @api
 */
interface GetProductBannerListInterface
{
    /**
     * Get ProductBanner list by search criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Uzer\Search\Api\Data\ProductBannerSearchResultsInterface
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): ProductBannerSearchResultsInterface;
}
