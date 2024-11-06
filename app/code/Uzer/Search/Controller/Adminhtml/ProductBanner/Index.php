<?php

namespace Uzer\Search\Controller\Adminhtml\ProductBanner;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * ProductBanner backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    const ADMIN_RESOURCE = 'Uzer_Search::management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Uzer_Search::management');
        $resultPage->addBreadcrumb(__('ProductBanner'), __('ProductBanner'));
        $resultPage->addBreadcrumb(__('Manage ProductBanners'), __('Manage ProductBanners'));
        $resultPage->getConfig()->getTitle()->prepend(__('ProductBanner List'));

        return $resultPage;
    }
}
