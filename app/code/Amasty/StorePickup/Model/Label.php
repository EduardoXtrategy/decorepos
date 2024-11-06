<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Model;

use Magento\Framework\Model\AbstractModel;

class Label extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Amasty\StorePickup\Model\ResourceModel\Label::class);
    }
}
