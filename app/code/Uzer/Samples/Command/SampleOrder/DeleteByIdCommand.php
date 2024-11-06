<?php

namespace Uzer\Samples\Command\SampleOrder;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Uzer\Samples\Model\ResourceModel\SampleOrder as ResourceModel;
use Uzer\Samples\Model\SampleOrder;
use Uzer\Samples\Model\SampleOrderFactory;

/**
 * Delete SampleOrder by id Command.
 */
class DeleteByIdCommand
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SampleOrderFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param SampleOrderFactory $modelFactory
     * @param ResourceModel $resource
     */
    public function __construct(
        LoggerInterface         $logger,
        SampleOrderFactory $modelFactory,
        ResourceModel $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Delete SampleOrder.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function execute(int $entityId)
    {
        try {
            /** @var SampleOrder $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, 'entity_id');

            if (!$model->getData('entity_id')) {
                throw new NoSuchEntityException(
                    __('Could not find SampleOrder with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete SampleOrder. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete SampleOrder.'));
        }
    }
}
