<?php

namespace Uzer\Search\Controller\Adminhtml\ProductBanner;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Uzer\Search\Api\Data\ProductBannerInterface;
use Uzer\Search\Api\DeleteProductBannerByIdInterface;

/**
 * Delete ProductBanner controller.
 */
class Delete extends Action implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Uzer_Search::management';

    /**
     * @var DeleteProductBannerByIdInterface
     */
    private $deleteByIdCommand;

    /**
     * @param Context $context
     * @param DeleteProductBannerByIdInterface $deleteByIdCommand
     */
    public function __construct(
        Context                          $context,
        DeleteProductBannerByIdInterface $deleteByIdCommand
    )
    {
        parent::__construct($context);
        $this->deleteByIdCommand = $deleteByIdCommand;
    }

    /**
     * Delete ProductBanner action.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var ResultInterface $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        $entityId = (int)$this->getRequest()->getParam(ProductBannerInterface::ENTITY_ID);

        try {
            $this->deleteByIdCommand->execute($entityId);
            $this->messageManager->addSuccessMessage(__('You have successfully deleted ProductBanner entity'));
        } catch (CouldNotDeleteException | NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $resultRedirect;
    }
}
