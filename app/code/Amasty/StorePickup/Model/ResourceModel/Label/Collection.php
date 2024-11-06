<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Model\ResourceModel\Label;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected function _construct()
    {
        $this->_init(
            \Amasty\StorePickup\Model\Label::class,
            \Amasty\StorePickup\Model\ResourceModel\Label::class
        );
    }

    public function addFiltersByMethodIdStoreId($methodId, $storeId)
    {
        $this->getSelect()->reset('where');
        $this->clear()
            ->addFieldToFilter('method_id', $methodId)
            ->addFieldToFilter('store_id', $storeId);

        return $this;
    }
}
