<?php

namespace Uzer\Samples\Controller\Adminhtml\SamplesCart;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Edit SamplesCart entity backend controller.
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Uzer_Samples::management';

    /**
     * Edit SamplesCart action.
     *
     * @return Page|ResultInterface
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Uzer_Samples::management');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit SamplesCart'));

        return $resultPage;
    }
}
