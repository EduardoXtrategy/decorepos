<?php

namespace Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Checkoutstep\Model\PurchaseOrder as Model;
use Uzer\Checkoutstep\Model\ResourceModel\PurchaseOrder as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_sales_purchase_order_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }


    public function addQuoteIdToFilter(int $quoteId): Collection
    {
        return $this->addFieldToFilter('quote_id', array('eq' => $quoteId));
    }
}
