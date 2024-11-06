<?php


namespace Uzer\Catalogs\Controller\Adminhtml\Catalog;


use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;

class Edit extends Action implements HttpGetActionInterface
{

    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Uzer_Catalogs::listing';

    public function execute()
    {
        $pageFactory = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $pageFactory->getConfig()->getTitle()->prepend(__('Edit Pdf Catalog'));
        return $pageFactory;
    }
}
