<?php

namespace Uzer\Search\Command\ProductBanner;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;
use Uzer\Search\Api\Data\ProductBannerInterface;
use Uzer\Search\Api\SaveProductBannerInterface;
use Uzer\Search\Model\ProductBannerModel;
use Uzer\Search\Model\ProductBannerModelFactory;
use Uzer\Search\Model\ResourceModel\ProductBannerResource;

/**
 * Save ProductBanner Command.
 */
class SaveCommand implements SaveProductBannerInterface
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
    public function execute(ProductBannerInterface $productBanner): int
    {
        try {
            /** @var ProductBannerModel $model */
            $model = $this->modelFactory->create();
            $model->addData($productBanner->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(ProductBannerInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save ProductBanner. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save ProductBanner.'));
        }

        return (int)$model->getData(ProductBannerInterface::ENTITY_ID);
    }
}
