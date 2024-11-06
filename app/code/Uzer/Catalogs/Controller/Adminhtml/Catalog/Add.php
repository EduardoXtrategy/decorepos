<?php


namespace Uzer\Catalogs\Controller\Adminhtml\Catalog;


use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Add extends Action implements HttpGetActionInterface
{

    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Uzer_Catalogs::listing';

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $pageFactory = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $pageFactory->getConfig()->getTitle()->prepend(__('Add Pdf Catalog'));
        return $pageFactory;
    }
}
