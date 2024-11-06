<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Controller\Adminhtml\Methods;

class Index extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = "Amasty_StorePickup::amstorepick";

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $pageResult */
        $pageResult = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $pageResult->setActiveMenu('Amasty_StorePickup::amstorepick');
        $pageResult->addBreadcrumb(__('Store Pickup'), __('Store Pickup'));
        $pageResult->getConfig()->getTitle()->prepend(__('Store Pickups Methods'));

        return $pageResult;
    }
}
