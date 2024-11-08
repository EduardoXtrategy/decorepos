<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Controller\Adminhtml\Methods;

use Amasty\StorePickup\Model\ResourceModel\Method\CollectionFactory;
use Magento\Backend\App\Action;

class MassDelete extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = "Amasty_StorePickup::amstorepick";

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        Action\Context $context,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $ids = $this->getRequest()->getParam('ids');

        try {
            /** @var $collection \Amasty\StorePickup\Model\ResourceModel\Method\Collection */
            $collection = $this->collectionFactory->create();

            $collection->addFieldToFilter('id', ['in' => $ids]);
            $collection->walk('delete');
            $this->messageManager->addSuccessMessage(__('Method(s) were successfully deleted'));
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage(
                $exception,
                __('We can\'t delete method(s) right now. Please review the log and try again. ')
                . $exception->getMessage()
            );
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
