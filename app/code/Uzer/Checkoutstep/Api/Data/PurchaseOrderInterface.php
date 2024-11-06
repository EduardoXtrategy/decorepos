<?php

namespace Uzer\Checkoutstep\Api\Data;

interface PurchaseOrderInterface
{
    /**
     * String constants for property names
     */
    public const QUOTE_ID = "quote_id";
    public const PO_NUMBER = "po_number";

    /**
     * Getter for QuoteId.
     *
     * @return int|null
     */
    public function getQuoteId(): ?int;

    /**
     * Setter for QuoteId.
     *
     * @param int|null $quoteId
     *
     * @return void
     */
    public function setQuoteId(?int $quoteId): void;

    /**
     * Getter for PoNumber.
     *
     * @return string|null
     */
    public function getPoNumber(): ?string;

    /**
     * Setter for PoNumber.
     *
     * @param string|null $poNumber
     *
     * @return void
     */
    public function setPoNumber(?string $poNumber): void;
}
