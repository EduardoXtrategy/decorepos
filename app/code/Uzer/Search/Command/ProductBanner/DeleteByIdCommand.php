<?php

namespace Uzer\Search\Command\ProductBanner;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Uzer\Search\Api\Data\ProductBannerInterface;
use Uzer\Search\Api\DeleteProductBannerByIdInterface;
use Uzer\Search\Model\ProductBannerModel;
use Uzer\Search\Model\ProductBannerModelFactory;
use Uzer\Search\Model\ResourceModel\ProductBannerResource;

/**
 * Delete ProductBanner by id Command.
 */
class DeleteByIdCommand implements DeleteProductBannerByIdInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProductBannerModelFactory
     */
    private $modelFactory;

    /**
     * @var ProductBannerResource
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param ProductBannerModelFactory $modelFactory
     * @param ProductBannerResource $resource
     */
    public function __construct(
        LoggerInterface           $logger,
        ProductBannerModelFactory $modelFactory,
        ProductBannerResource     $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    public function execute(int $entityId): void
    {
        try {
            /** @var ProductBannerModel $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, ProductBannerInterface::ENTITY_ID);

            if (!$model->getData(ProductBannerInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find ProductBanner with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete ProductBanner. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete ProductBanner.'));
        }
    }
}
