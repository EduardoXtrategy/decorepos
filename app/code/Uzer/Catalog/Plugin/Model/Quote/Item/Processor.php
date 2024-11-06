<?php

namespace Uzer\Catalog\Plugin\Model\Quote\Item;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModelFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Item\Processor as ItemProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Catalog\Helper\Data;
use Uzer\Catalog\Logger\Logger;
use Uzer\Catalog\Model\Common;

class Processor
{

    protected StoreManagerInterface $storeManager;
    protected ResourceModelFactory $resourceModel;
    protected ProductFactory $productFactory;
    protected Data $helperData;
    protected Logger $logger;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ResourceModelFactory $resourceModel
     * @param ProductFactory $productFactory
     * @param Data $helperData
     * @param Logger $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ResourceModelFactory  $resourceModel,
        ProductFactory        $productFactory,
        Data                  $helperData,
        Logger                $logger
    )
    {
        $this->storeManager = $storeManager;
        $this->resourceModel = $resourceModel;
        $this->productFactory = $productFactory;
        $this->helperData = $helperData;
        $this->logger = $logger;
    }


//    public function beforePrepare(
//        ItemProcessor $processor,
//        Item          $item,
//        DataObject    $request,
//        Product       $candidate
//    )
//    {
////        $enable = $this->helperData->isEnable($this->storeManager->getStore()->getId());
//        $enable = false;
//        if ($enable) {
//            $id = $candidate->getIdBySku($candidate->getSku());
//            $entity = $this->productFactory->create();
//            $this->resourceModel->create()->load($entity, $id);
//            $boxSize = $entity->getBoxSize();
//            if ($boxSize) {
//                $this->logger->info(json_encode($request));
//                //$candidate->setCartQty($candidate->getCartQty() * (int)$boxSize);
//            }
//        }
//    }

}
