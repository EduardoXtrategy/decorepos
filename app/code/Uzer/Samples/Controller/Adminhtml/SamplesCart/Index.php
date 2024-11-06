<?php

namespace Uzer\Samples\Controller\Adminhtml\SamplesCart;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * SamplesCart backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    const ADMIN_RESOURCE = 'Uzer_Samples::management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Uzer_Samples::management');
        $resultPage->addBreadcrumb(__('SamplesCart'), __('SamplesCart'));
        $resultPage->addBreadcrumb(__('Manage SamplesCarts'), __('Manage SamplesCarts'));
        $resultPage->getConfig()->getTitle()->prepend(__('SamplesCart List'));

        return $resultPage;
    }
}
