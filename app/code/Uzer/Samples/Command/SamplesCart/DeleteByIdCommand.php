<?php

namespace Uzer\Samples\Command\SamplesCart;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Uzer\Samples\Model\ResourceModel\SamplesCart as ResourceModel;
use Uzer\Samples\Model\SamplesCart;
use Uzer\Samples\Model\SamplesCartFactory;

/**
 * Delete SamplesCart by id Command.
 */
class DeleteByIdCommand
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SamplesCartFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param SamplesCartFactory $modelFactory
     * @param ResourceModel $resource
     */
    public function __construct(
        LoggerInterface         $logger,
        SamplesCartFactory $modelFactory,
        ResourceModel $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Delete SamplesCart.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function execute(int $entityId)
    {
        try {
            /** @var SamplesCart $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, 'entity_id');

            if (!$model->getData('entity_id')) {
                throw new NoSuchEntityException(
                    __('Could not find SamplesCart with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete SamplesCart. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete SamplesCart.'));
        }
    }
}
