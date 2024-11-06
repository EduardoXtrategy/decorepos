<?php

namespace Uzer\Samples\Command\SampleOrder;

use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Uzer\Samples\Api\Data\SampleOrderInterface;
use Uzer\Samples\Model\ResourceModel\SampleOrder as ResourceModel;
use Uzer\Samples\Model\SampleOrder;
use Uzer\Samples\Model\SampleOrderFactory;

/**
 * Save SampleOrder Command.
 */
class SaveCommand
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
        LoggerInterface    $logger,
        SampleOrderFactory $modelFactory,
        ResourceModel      $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Save SampleOrder.
     *
     * @param SampleOrderInterface|DataObject $sampleOrder
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(SampleOrderInterface $sampleOrder): int
    {
        try {
            /** @var SampleOrder $model */
            $model = $this->modelFactory->create();
            $model->addData($sampleOrder->getData());
            $model->setHasDataChanges(true);

            if (!$model->getId()) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save SampleOrder. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save SampleOrder.'));
        }

        return (int)$model->getEntityId();
    }
}
