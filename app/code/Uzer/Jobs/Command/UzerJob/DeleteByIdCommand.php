<?php

namespace Uzer\Jobs\Command\UzerJob;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Uzer\Jobs\Model\ResourceModel\UzerJob as ResourceModel;
use Uzer\Jobs\Model\UzerJob;
use Uzer\Jobs\Model\UzerJobFactory;

/**
 * Delete UzerJob by id Command.
 */
class DeleteByIdCommand
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UzerJobFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param UzerJobFactory $modelFactory
     * @param ResourceModel $resource
     */
    public function __construct(
        LoggerInterface $logger,
        UzerJobFactory $modelFactory,
        ResourceModel $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Delete UzerJob.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function execute(int $entityId)
    {
        try {
            /** @var UzerJob $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, 'entity_id');

            if (!$model->getData('entity_id')) {
                throw new NoSuchEntityException(
                    __('Could not find UzerJob with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete UzerJob. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete UzerJob.'));
        }
    }
}
