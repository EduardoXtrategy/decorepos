<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Controller\Adminhtml\Methods;

use Amasty\StorePickup\Model\MethodFactory;
use Magento\Backend\App\Action;

class MassActivate extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = "Amasty_StorePickup::amstorepick";

    /**
     * @var MethodFactory
     */
    private $methodFactory;

    public function __construct(
        Action\Context $context,
        MethodFactory $methodFactory
    ) {
        parent::__construct($context);
        $this->methodFactory = $methodFactory;
    }

    public function execute()
    {
        $ids = $this->getRequest()->getParam('ids');
        $status = $this->getRequest()->getParam('activate');

        try {
            /** @var \Amasty\StorePickup\Model\Method $methodsModel */
            $methodsModel = $this->methodFactory->create();
            $methodsModel->massChangeStatus($ids, $status);
            $this->messageManager->addSuccessMessage(__('Record(s) have been updated.'));
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage(
                $exception,
                __('We can\'t activate method(s) right now. Please review the log and try again. ')
                . $exception->getMessage()
            );
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
