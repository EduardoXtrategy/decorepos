<?php

namespace Uzer\CreditTerms\Command\CustomerBalance;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Uzer\CreditTerms\Api\Data\CustomerBalanceInterface;
use Uzer\CreditTerms\Api\DeleteCustomerBalanceByIdInterface;
use Uzer\CreditTerms\Model\CustomerBalance;
use Uzer\CreditTerms\Model\CustomerBalanceFactory;
use Uzer\CreditTerms\Model\ResourceModel\CustomerBalance as ResourceModel;

/**
 * Delete CustomerBalance by id Command.
 */
class DeleteByIdCommand implements DeleteCustomerBalanceByIdInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CustomerBalanceFactory
     */
    private CustomerBalanceFactory $modelFactory;

    /**
     * @var CustomerBalance
     */
    private ResourceModel $resource;

    /**
     * @param LoggerInterface $logger
     * @param CustomerBalanceFactory $modelFactory
     * @param ResourceModel $resource
     */
    public function __construct(
        LoggerInterface        $logger,
        CustomerBalanceFactory $modelFactory,
        ResourceModel        $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    public function execute(int $entityId): void
    {
        try {
            /** @var CustomerBalance $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, CustomerBalanceInterface::ENTITY_ID);

            if (!$model->getData(CustomerBalanceInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find CustomerBalance with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete CustomerBalance. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete CustomerBalance.'));
        }
    }
}
