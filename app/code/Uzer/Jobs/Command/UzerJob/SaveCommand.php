<?php

namespace Uzer\Jobs\Command\UzerJob;

use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Uzer\Jobs\Api\Data\UzerJobInterface;
use Uzer\Jobs\Model\ResourceModel\UzerJob as ResourceModel;
use Uzer\Jobs\Model\UzerJob;
use Uzer\Jobs\Model\UzerJobFactory;

/**
 * Save UzerJob Command.
 */
class SaveCommand
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
     * @var UzerJob
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
     * Save UzerJob.
     *
     * @param UzerJobInterface|DataObject $uzerJob
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(UzerJobInterface $uzerJob): int
    {
        try {
            /** @var UzerJob $model */
            $model = $this->modelFactory->create();
            $model->addData($uzerJob->getData());
            $model->setHasDataChanges(true);

            if (!$model->getId()) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save UzerJob. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save UzerJob.'));
        }

        return (int)$model->getEntityId();
    }
}
