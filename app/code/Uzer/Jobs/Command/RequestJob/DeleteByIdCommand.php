<?php

namespace Uzer\Jobs\Command\RequestJob;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Uzer\Jobs\Model\RequestJob;
use Uzer\Jobs\Model\RequestJobFactory;
use Uzer\Jobs\Model\ResourceModel\RequestJob as ResourceModel;

/**
 * Delete RequestJob by id Command.
 */
class DeleteByIdCommand
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RequestJobFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param RequestJobFactory $modelFactory
     * @param ResourceModel $resource
     */
    public function __construct(
        LoggerInterface $logger,
        RequestJobFactory $modelFactory,
        ResourceModel $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Delete RequestJob.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function execute(int $entityId)
    {
        try {
            /** @var RequestJob $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, 'entity_id');

            if (!$model->getData('entity_id')) {
                throw new NoSuchEntityException(
                    __('Could not find RequestJob with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete RequestJob. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete RequestJob.'));
        }
    }
}
