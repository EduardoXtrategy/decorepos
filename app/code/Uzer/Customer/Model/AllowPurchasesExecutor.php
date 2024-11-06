<?php

namespace Uzer\Customer\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Customer\Helper\Data;

class AllowPurchasesExecutor
{

    protected Session $session;
    protected StoreManagerInterface $storeManager;
    protected Data $helperData;

    /**
     * @param Session $session
     * @param StoreManagerInterface $storeManager
     * @param Data $helperData
     */
    public function __construct(Session $session, StoreManagerInterface $storeManager, Data $helperData)
    {
        $this->session = $session;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
    }


    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function isAllowPurchase(): bool
    {
        if (!$this->session->isLoggedIn()) {
            return false;
        }

        $customerGroupId = $this->session->getCustomerGroupId();
        $groupId = $this->helperData->group($this->storeManager->getStore()->getId());
        $excludedGroupId = $this->helperData->getExcludedGroup($this->storeManager->getStore()->getId());
        return $groupId == $customerGroupId || $excludedGroupId == $customerGroupId;
    }


}
