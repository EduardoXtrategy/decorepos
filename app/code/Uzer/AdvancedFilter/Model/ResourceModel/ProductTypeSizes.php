<?php

namespace Uzer\AdvancedFilter\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Uzer\AdvancedFilter\Api\Data\ProductTypeSizesInterface;

class ProductTypeSizes extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'product_type_sizes_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('product_type_sizes', ProductTypeSizesInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }


    /**
     * @throws LocalizedException
     */
    public function saveMany(array $data)
    {
        $this->getConnection()->insertMultiple($this->getMainTable(), $data);
    }
}
