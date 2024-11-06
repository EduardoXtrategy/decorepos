<?php

namespace Uzer\Jobs\Command\RequestJob;

use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Uzer\Jobs\Api\Data\RequestJobInterface;
use Uzer\Jobs\Model\RequestJob;
use Uzer\Jobs\Model\RequestJobFactory;
use Uzer\Jobs\Model\ResourceModel\RequestJob as ResourceModel;

/**
 * Save RequestJob Command.
 */
class SaveCommand
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
     * Save RequestJob.
     *
     * @param RequestJobInterface|DataObject $requestJob
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(RequestJobInterface $requestJob): int
    {
        try {
            /** @var RequestJob $model */
            $model = $this->modelFactory->create();
            $model->addData($requestJob->getData());
            $model->setHasDataChanges(true);

            if (!$model->getId()) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save RequestJob. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save RequestJob.'));
        }

        return (int)$model->getEntityId();
    }
}
