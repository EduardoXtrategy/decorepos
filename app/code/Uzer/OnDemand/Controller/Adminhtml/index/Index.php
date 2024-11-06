<?php

namespace Uzer\OnDemand\Controller\Adminhtml\index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Uzer_OnDemand::listing';

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Uzer_OnDemand::listing');
        $resultPage->addBreadcrumb(__('OndemandRequests'), __('OndemandRequests'));
        $resultPage->addBreadcrumb(__('On Demand requests'), __('On demand requests'));
        $resultPage->getConfig()->getTitle()->prepend(__('On demand Listing'));
        return $resultPage;
    }
}
