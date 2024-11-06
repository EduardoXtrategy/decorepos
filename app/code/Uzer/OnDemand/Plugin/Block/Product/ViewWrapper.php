<?php

namespace Uzer\OnDemand\Plugin\Block\Product;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Catalog\Block\Product\View;
use Uzer\OnDemand\Helper\Data;

class ViewWrapper
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
    public function afterDisplayAddToCart(View $subject, bool $result): bool
    {
        $preSaleId = $this->helperData->getPreSaleAttribute($this->_storeManager->getStore()->getId());
        $onDemandId = $this->helperData->getOnDemandAttribute($this->_storeManager->getStore()->getId());
        $bModel = $subject->getProduct()->getBModel();
        if ($bModel == $onDemandId || $bModel == $preSaleId) {
            return false;
        }
        return true;
    }

}
