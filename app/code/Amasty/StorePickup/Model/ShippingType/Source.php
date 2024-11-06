<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Model\ShippingType;

class Source extends \Magento\Eav\Model\Entity\Attribute\Source\Table
{
    /**
     * @param bool $withEmpty
     * @param bool $defaultValues
     *
     * @return array
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        if (!$this->_options) {
            $this->_options = parent::getAllOptions($withEmpty, $defaultValues);
            $this->_options[0]['value'] = 0;
            $this->_options[0]['label'] = __('None');
        }

        return $this->_options;
    }
}
