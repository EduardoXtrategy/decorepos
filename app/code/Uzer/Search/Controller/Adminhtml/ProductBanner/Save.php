<?php

namespace Uzer\Search\Controller\Adminhtml\ProductBanner;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Uzer\Search\Api\Data\ProductBannerInterface;
use Uzer\Search\Api\Data\ProductBannerInterfaceFactory;
use Uzer\Search\Api\SaveProductBannerInterface;

/**
 * Save ProductBanner controller action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Uzer_Search::management';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var SaveProductBannerInterface
     */
    private $saveCommand;

    /**
     * @var ProductBannerInterfaceFactory
     */
    private $entityDataFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SaveProductBannerInterface $saveCommand
     * @param ProductBannerInterfaceFactory $entityDataFactory
     */
    public function __construct(
        Context                       $context,
        DataPersistorInterface        $dataPersistor,
        SaveProductBannerInterface    $saveCommand,
        ProductBannerInterfaceFactory $entityDataFactory
    )
    {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->saveCommand = $saveCommand;
        $this->entityDataFactory = $entityDataFactory;
    }

    /**
     * Save ProductBanner Action.
     *
     * @return ResponseInterface|ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            /** @var ProductBannerInterface|DataObject $entityModel */
            $entityModel = $this->entityDataFactory->create();
            $entityModel->addData($params['general']);
            $entityModel->setAttributeName($this->getAttributeName());
            $this->saveCommand->execute($entityModel);
            $this->messageManager->addSuccessMessage(
                __('The ProductBanner data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);

            return $resultRedirect->setPath('*/*/edit', [
                ProductBannerInterface::ENTITY_ID => $this->getRequest()->getParam(ProductBannerInterface::ENTITY_ID)
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttributeName(): string
    {
        $eavConfig = ObjectManager::getInstance()->create(\Magento\Eav\Model\Config::class);
        $attribute = $eavConfig->getAttribute('catalog_product', 'product_type_decowraps');
        $label = '';
        $data = $this->getRequest()->getParam('general');
        if (!isset($data['attribute_id']) || $data['attribute_id'] == 0) {
            return 'Default banner';
        }
        $id = $data['attribute_id'];
        if ($attribute) {
            foreach ($attribute->getOptions() as $option) {
                if ($option->getValue() == $id) {
                    $label = $option->getLabel();
                    break;
                }
            }
        }
        return $label;
    }
}
