<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Model\Config\Source;

use Amasty\StorePickup\Helper\Config as HelperConfig;

class Volumetrictype implements \Magento\Framework\Data\OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            HelperConfig::VOLUMETRIC_WEIGHT_ATTRIBUTE_TYPE => __('Volumetric weight attribute'),
            HelperConfig::VOLUMETRIC_ATTRIBUTE_TYPE => __('Volume attribute'),
            HelperConfig::VOLUMETRIC_DIMENSIONS_ATTRIBUTE => __('Dimensions attribute'),
            HelperConfig::VOLUMETRIC_SEPARATE_DIMENSION_ATTRIBUTE => __('Separate dimension attribute')
        ];
    }
}
