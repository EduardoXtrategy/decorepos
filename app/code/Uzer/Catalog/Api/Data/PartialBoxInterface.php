<?php

namespace Uzer\Catalog\Api\Data;

interface PartialBoxInterface
{

    const ENTITY_ID = 'entity_id';
    const QUOTE_ID = 'quote_id';
    const QTY = 'qty';
    const QUOTE_ITEM_ID = 'quote_item_id';
    const PRODUCT_ID = 'product_id';

    /**
     * @return int
     */
    public function getQty(): int;

    /**
     * @param int $qty
     * @return PartialBoxInterface
     */
    public function setQty(int $qty): PartialBoxInterface;

    /**
     * @return int
     */
    public function getQuoteId(): int;

    /**
     * @param int $quoteId
     * @return PartialBoxInterface
     */
    public function setQuoteId(int $quoteId): PartialBoxInterface;

    /**
     * @return int
     */
    public function getQuoteItemId(): int;

    /**
     * @param int $quoteItemId
     * @return PartialBoxInterface
     */
    public function setQuoteItemId(int $quoteItemId): PartialBoxInterface;

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @param int $productId
     * @return PartialBoxInterface
     */
    public function setProductId(int $productId): PartialBoxInterface;
}
