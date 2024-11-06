<?php

namespace Uzer\Catalog\Api;

use Uzer\Catalog\Api\Data\PartialBoxInterface;

interface PartialBoxQuoteInterface
{

    /**
     * @param PartialBoxInterface $partialBox
     * @return PartialBoxInterface
     * @api
     */
    public function add(PartialBoxInterface $partialBox): PartialBoxInterface;

    /**
     * @param PartialBoxInterface $partialBox
     * @return PartialBoxInterface
     * @api
     */
    public function register(PartialBoxInterface $partialBox): PartialBoxInterface;

    /**
     * @param int $quoteId
     * @return PartialBoxInterface|null
     * @api
     */
    public function getByQuoteItemId(int $quoteId): ?PartialBoxInterface;

    /**
     * @param int $quoteId
     * @param int $productId
     * @return PartialBoxInterface|null
     * @api
     */
    public function getByQuiteIdAndProductId(int $quoteId, int $productId): ?PartialBoxInterface;


}
