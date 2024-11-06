<?php

namespace Uzer\Jobs\Controller\Adminhtml\UzerJob;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Uzer\Jobs\Api\Data\UzerJobInterface;
use Uzer\Jobs\Api\Data\UzerJobInterfaceFactory;
use Uzer\Jobs\Command\UzerJob\SaveCommand;

/**
 * Save UzerJob controller action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Uzer_Jobs::management';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var SaveCommand
     */
    private $saveCommand;

    /**
     * @var UzerJobInterfaceFactory
     */
    private $entityDataFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SaveCommand $saveCommand
     * @param UzerJobInterfaceFactory $entityDataFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        SaveCommand $saveCommand,
        UzerJobInterfaceFactory $entityDataFactory
    )
    {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->saveCommand = $saveCommand;
        $this->entityDataFactory = $entityDataFactory;
    }

    /**
     * Save UzerJob Action.
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            /** @var UzerJobInterface|DataObject $entityModel */
            $entityModel = $this->entityDataFactory->create();
            $entityModel->addData($params['general']);
            $this->saveCommand->execute($entityModel);
            $this->messageManager->addSuccessMessage(
                __('The UzerJob data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);

            return $resultRedirect->setPath('*/*/edit', [
                'entity_id' => $this->getRequest()->getParam('entity_id')
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
