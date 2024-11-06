<?php

namespace Uzer\Search\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Uzer\Search\Api\Data\ProductBannerInterface;

/**
 * Save ProductBanner Command.
 *
 * @api
 */
interface SaveProductBannerInterface
{
    /**
     * Save ProductBanner.
     * @param \Uzer\Search\Api\Data\ProductBannerInterface $productBanner
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(ProductBannerInterface $productBanner): int;
}
