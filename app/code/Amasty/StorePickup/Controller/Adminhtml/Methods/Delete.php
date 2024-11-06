<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Controller\Adminhtml\Methods;

use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = "Amasty_StorePickup::amstorepick";

    /**
     * @var \Amasty\StorePickup\Model\MethodFactory
     */
    private $methodFactory;

    /**
     * @var \Amasty\StorePickup\Model\ResourceModel\Method
     */
    private $methodResource;

    public function __construct(
        Action\Context $context,
        \Amasty\StorePickup\Model\MethodFactory $methodFactory,
        \Amasty\StorePickup\Model\ResourceModel\Method $methodResource
    ) {
        parent::__construct($context);

        $this->methodFactory = $methodFactory;
        $this->methodResource = $methodResource;
    }

    public function execute()
    {
        $methodId = $this->getRequest()->getParam('id');
        /** @var \Amasty\StorePickup\Model\Method $model */
        $model = $this->methodFactory->create();
        $this->methodResource->load($model, $methodId);

        if ($methodId && !$model->getId()) {
            $this->messageManager->addErrorMessage(__('Record does not exist'));

            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        try {
            $this->methodResource->delete($model);
            $this->messageManager->addSuccessMessage(
                __('Shipping method has been successfully deleted')
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
