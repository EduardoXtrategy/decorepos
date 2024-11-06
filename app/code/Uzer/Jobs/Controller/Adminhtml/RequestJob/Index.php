<?php

namespace Uzer\Jobs\Controller\Adminhtml\RequestJob;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * RequestJob backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    const ADMIN_RESOURCE = 'Uzer_Jobs::requests';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Uzer_Jobs::requests');
        $resultPage->addBreadcrumb(__('RequestJob'), __('RequestJob'));
        $resultPage->addBreadcrumb(__('Manage RequestJobs'), __('Manage RequestJobs'));
        $resultPage->getConfig()->getTitle()->prepend(__('RequestJob List'));

        return $resultPage;
    }
}
