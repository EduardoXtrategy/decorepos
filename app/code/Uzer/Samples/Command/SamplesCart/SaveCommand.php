<?php

namespace Uzer\Samples\Command\SamplesCart;

use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Uzer\Samples\Api\Data\SamplesCartInterface;
use Uzer\Samples\Model\ResourceModel\SamplesCart as ResourceModel;
use Uzer\Samples\Model\SamplesCart;
use Uzer\Samples\Model\SamplesCartFactory;

/**
 * Save SamplesCart Command.
 */
class SaveCommand
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
        LoggerInterface    $logger,
        SamplesCartFactory $modelFactory,
        ResourceModel      $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Save SamplesCart.
     *
     * @param SamplesCartInterface|DataObject $samplesCart
     *
     * @return SamplesCart
     * @throws CouldNotSaveException
     */
    public function execute(SamplesCartInterface $samplesCart)
    {
        try {
            /** @var SamplesCart $model */
            $model = $this->modelFactory->create();
            $model->addData($samplesCart->getData());
            $model->setHasDataChanges(true);

            if (!$model->getId()) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save SamplesCart. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save SamplesCart.'));
        }

        return $model;
    }
}
