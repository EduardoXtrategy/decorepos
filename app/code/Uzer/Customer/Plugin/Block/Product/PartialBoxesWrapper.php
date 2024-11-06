<?php

namespace Uzer\Customer\Plugin\Block\Product;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Catalog\Block\Product\PartialBoxes;
use Uzer\Customer\Helper\Data;

class PartialBoxesWrapper
{

    protected StoreManagerInterface $storeManager;
    protected Session $session;
    protected Data $helperData;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Session $session
     * @param Data $helperData
     */
    public function __construct(StoreManagerInterface $storeManager, Session $session, Data $helperData)
    {
        $this->storeManager = $storeManager;
        $this->session = $session;
        $this->helperData = $helperData;
    }


    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function afterHasDisplay(PartialBoxes $subject, bool $result): bool
    {
        if (!$result) {
            return false;
        }
        $storeId = $this->storeManager->getStore()->getId();
        $availableGroups = [$this->helperData->group($storeId), $this->helperData->getExcludedGroup($storeId)];
        return in_array($this->session->getCustomerGroupId(), $availableGroups);
    }

}
