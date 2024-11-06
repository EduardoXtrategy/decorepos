<?php

namespace Uzer\AdvancedFilter\Command\ProductTypeSizes;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Uzer\AdvancedFilter\Api\Data\ProductTypeSizesInterface;
use Uzer\AdvancedFilter\Model\ProductTypeSizes;
use Uzer\AdvancedFilter\Model\ProductTypeSizesFactory;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes as ResourceModel;

/**
 * Save ProductTypeSizes Command.
 */
class SaveCommand
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProductTypeSizesFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param ProductTypeSizesFactory $modelFactory
     * @param ResourceModel $resource
     */
    public function __construct(
        LoggerInterface              $logger,
        ProductTypeSizesFactory $modelFactory,
        ResourceModel $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Save ProductTypeSizes.
     *
     * @param ProductTypeSizesInterface $productTypeSizes
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(ProductTypeSizesInterface $productTypeSizes): int
    {
        try {
            /** @var ProductTypeSizes $model */
            $model = $this->modelFactory->create();
            $model->addData($productTypeSizes->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(ProductTypeSizesInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save ProductTypeSizes. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save ProductTypeSizes.'));
        }

        return (int)$model->getData(ProductTypeSizesInterface::ENTITY_ID);
    }
}
