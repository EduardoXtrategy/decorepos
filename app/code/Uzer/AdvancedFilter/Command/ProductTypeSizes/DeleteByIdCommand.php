<?php

namespace Uzer\AdvancedFilter\Command\ProductTypeSizes;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Uzer\AdvancedFilter\Api\Data\ProductTypeSizesInterface;
use Uzer\AdvancedFilter\Model\ProductTypeSizes;
use Uzer\AdvancedFilter\Model\ProductTypeSizesFactory;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes as ResourceModel;

/**
 * Delete ProductTypeSizes by id Command.
 */
class DeleteByIdCommand
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
     * Delete ProductTypeSizes.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void
    {
        try {
            /** @var ProductTypeSizes $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, ProductTypeSizesInterface::ENTITY_ID);

            if (!$model->getData(ProductTypeSizesInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find ProductTypeSizes with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete ProductTypeSizes. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete ProductTypeSizes.'));
        }
    }
}
