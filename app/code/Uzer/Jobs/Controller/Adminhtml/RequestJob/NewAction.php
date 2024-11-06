<?php

namespace Uzer\Jobs\Controller\Adminhtml\RequestJob;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * New action RequestJob controller.
 */
class NewAction extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Uzer_Jobs::requests';

    /**
     * Create new RequestJob action.
     *
     * @return Page|ResultInterface
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Uzer_Jobs::requests');
        $resultPage->getConfig()->getTitle()->prepend(__('New RequestJob'));

        return $resultPage;
    }
}
