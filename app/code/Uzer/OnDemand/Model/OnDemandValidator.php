<?php

namespace Uzer\OnDemand\Model;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\OnDemand\Helper\Data;

class OnDemandValidator
{

    protected StoreManagerInterface $_storeManager;
    protected Data $helperData;

    /**
     * @param StoreManagerInterface $_storeManager
     * @param Data $helperData
     */
    public function __construct(StoreManagerInterface $_storeManager, Data $helperData)
    {
        $this->_storeManager = $_storeManager;
        $this->helperData = $helperData;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function isOndemand(Product $product): bool
    {
        $preSaleId = $this->helperData->getPreSaleAttribute($this->_storeManager->getStore()->getId());
        $ondemandId = $this->helperData->getOnDemandAttribute($this->_storeManager->getStore()->getId());
        $bModel = $product->getBModel();
        if ($bModel == $ondemandId || $bModel == $preSaleId) {
            return true;
        }
        return false;
    }

}
