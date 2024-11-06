<?php

namespace Uzer\CreditTerms\Command\CustomerBalance;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterface;
use Uzer\CreditTerms\Api\SaveCustomerBalanceInterface;
use Uzer\CreditTerms\Model\CustomerBalanceFactory;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance as ResourceModel;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance\CollectionFactory;

/**
 * Save CustomerBalance Command.
 */
class SaveCommand implements SaveCustomerBalanceInterface
{

    private LoggerInterface $logger;
    private CustomerBalanceFactory $modelFactory;
    private ResourceModel $resource;
    private CollectionFactory $collectionFactory;

    /**
     * @param LoggerInterface $logger
     * @param CustomerBalanceFactory $modelFactory
     * @param ResourceModel $resource
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        LoggerInterface        $logger,
        CustomerBalanceFactory $modelFactory,
        ResourceModel          $resource,
        CollectionFactory      $collectionFactory
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(CustomerBalanceInterface $customerBalance): CustomerBalanceInterface
    {
        try {
            $model = $this->collectionFactory->create()
                ->addFieldToFilter('customers_id', array('eq' => $customerBalance->getCustomersId()))
                ->load()
                ->getFirstItem();
            if (!$model->hasData()) {
                $model = $this->modelFactory->create();
            }
            $model->addData($customerBalance->getData());
            $model->setHasDataChanges(true);
            if (!$model->getData(CustomerBalanceInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
            return $model;
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save CustomerBalance. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save CustomerBalance.'));
        }
    }
}
