<?php

namespace Uzer\CustomProducts\Model\ResourceModel\CustomerProduct;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\CustomProducts\Model\CustomerProduct as Model;
use Uzer\CustomProducts\Model\ResourceModel\CustomerProduct as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customer_products_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    public function joinToCustomer(): Collection
    {
        $this->getSelect()->join(
            ['cc' => $this->getTable('category_customers')],
            'main_table.customers_id = cc.customer_id',
            ['category_id']
        )->join(
            ['ce' => $this->getTable('customer_entity')],
            'main_table.customers_id = ce.entity_id',
            ['firstname', 'lastname']
        );
        return $this;
    }
}
