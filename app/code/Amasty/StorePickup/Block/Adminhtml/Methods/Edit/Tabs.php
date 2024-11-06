<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Block\Adminhtml\Methods\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('amstorepick_methods_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Method Options'));
    }

    protected function _beforeToHtml()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if ($tabId) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if ($tabId) {
                $this->setActiveTab($tabId);
            }
        } else {
            $this->setActiveTab('main');
        }

        $this->assign('tabs', $this->_tabs);

        return parent::_beforeToHtml();
    }
}
