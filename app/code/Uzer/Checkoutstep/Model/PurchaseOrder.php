<?php

namespace Uzer\Checkoutstep\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Checkoutstep\Api\Data\PurchaseOrderInterface;
use Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrder as ResourceModel;

class PurchaseOrder extends AbstractModel implements PurchaseOrderInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_sales_purchase_order_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }


    /**
     * Getter for QuoteId.
     *
     * @return int|null
     */
    public function getQuoteId(): ?int
    {
        return $this->getData(self::QUOTE_ID) === null ? null
            : (int)$this->getData(self::QUOTE_ID);
    }

    /**
     * Setter for QuoteId.
     *
     * @param int|null $quoteId
     *
     * @return void
     */
    public function setQuoteId(?int $quoteId): void
    {
        $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * Getter for PoNumber.
     *
     * @return string|null
     */
    public function getPoNumber(): ?string
    {
        return $this->getData(self::PO_NUMBER);
    }

    /**
     * Setter for PoNumber.
     *
     * @param string|null $poNumber
     *
     * @return void
     */
    public function setPoNumber(?string $poNumber): void
    {
        $this->setData(self::PO_NUMBER, $poNumber);
    }
}
