<?php

namespace Uzer\MostviewedProducts\Observer;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModelFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Reports\Model\Product\Index\ViewedFactory;
use Magento\Reports\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class CatalogProductView implements ObserverInterface
{

    protected ResourceModelFactory $resourceModel;
    protected ProductFactory $productFactory;
    protected CollectionFactory $collectionFactory;
    protected ViewedFactory $viewedFactory;
    protected LoggerInterface $logger;

    /**
     * @param ResourceModelFactory $resourceModel
     * @param ProductFactory $productFactory
     * @param CollectionFactory $collectionFactory
     * @param ViewedFactory $viewedFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceModelFactory $resourceModel,
        ProductFactory       $productFactory,
        CollectionFactory    $collectionFactory,
        ViewedFactory        $viewedFactory,
        LoggerInterface      $logger
    )
    {
        $this->resourceModel = $resourceModel;
        $this->productFactory = $productFactory;
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
        $this->viewedFactory = $viewedFactory;
    }


    public function execute(Observer $observer)
    {
        $this->logger->info('Updating count visit product');
        /** @var Product $product */
        $product = $observer->getData('product');
        $this->collectionFactory->create()->setProductEntityId($product->getId())->addViewsCount()->getData();
        $this->viewedFactory->create()->setProductId($product->getId())->save();
        $resource = ObjectManager::getInstance()->create(\Magento\Framework\App\ResourceConnection::class);
        $storeManager = ObjectManager::getInstance()->create(StoreManagerInterface::class);
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $connection->insert('report_event', array(
            'logged_at' => date('Y-m-d H:i:s'),
            'event_type_id' => 1,
            'object_id' => $product->getId(),
            'subject_id' => $product->getId(),
            'subtype' => 0,
            'store_id' => $storeManager->getStore()->getId()
        ));
    }
}
