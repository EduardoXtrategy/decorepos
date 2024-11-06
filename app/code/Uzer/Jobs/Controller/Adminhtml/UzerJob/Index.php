<?php

namespace Uzer\Jobs\Controller\Adminhtml\UzerJob;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * UzerJob backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    const ADMIN_RESOURCE = 'Uzer_Jobs::management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Uzer_Jobs::jobs');
        $resultPage->addBreadcrumb(__('UzerJob'), __('UzerJob'));
        $resultPage->addBreadcrumb(__('Manage UzerJobs'), __('Manage UzerJobs'));
        $resultPage->getConfig()->getTitle()->prepend(__('Job List'));

        return $resultPage;
    }
}
