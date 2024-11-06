<?php

namespace Uzer\Catalog\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Catalog\Api\Data\PartialBoxInterface;
use Uzer\Catalog\Model\ResourceModel\PartialBox as ResourceModel;

class PartialBox extends AbstractModel implements PartialBoxInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_quotes_partial_boxes';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getQty(): int
    {
        return $this->getData(self::QTY);
    }

    public function setQty(int $qty): PartialBoxInterface
    {
        return $this->setData(self::QTY, $qty);
    }

    public function getQuoteId(): int
    {
        return $this->getData(self::QUOTE_ID);
    }

    public function setQuoteId(int $quoteId): PartialBoxInterface
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    public function getQuoteItemId(): int
    {
        return $this->getData(self::QUOTE_ITEM_ID);
    }

    public function setQuoteItemId(int $quoteItemId): PartialBoxInterface
    {
        return $this->setData(self::QUOTE_ITEM_ID, $quoteItemId);
    }

    public function getProductId(): int
    {
        return $this->getData(self::PRODUCT_ID);
    }

    public function setProductId(int $productId): PartialBoxInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }
}
