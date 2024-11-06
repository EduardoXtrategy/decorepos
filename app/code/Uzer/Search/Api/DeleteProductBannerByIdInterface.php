<?php

namespace Uzer\Search\Api;

use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete ProductBanner by id Command.
 *
 * @api
 */
interface DeleteProductBannerByIdInterface
{
    /**
     * Delete ProductBanner.
     * @param int $entityId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void;
}
