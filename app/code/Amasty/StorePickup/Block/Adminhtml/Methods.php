<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Block\Adminhtml;

class Methods extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function _construct()
    {
        $this->_controller = 'adminhtml_methods';
        $this->_headerText = __('Store Pickup');
        $this->_blockGroup = 'Amasty_StorePickup';
        parent::_construct();
    }
}
