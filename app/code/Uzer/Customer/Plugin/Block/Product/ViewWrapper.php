<?php

namespace Uzer\Customer\Plugin\Block\Product;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Catalog\Block\Product\View;
use Uzer\Customer\Helper\Data;

class ViewWrapper
{
    protected StoreManagerInterface $storeManager;
    protected Session $session;
    protected Data $data;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Session $session
     * @param Data $data
     */
    public function __construct(StoreManagerInterface $storeManager, Session $session, Data $data)
    {
        $this->storeManager = $storeManager;
        $this->session = $session;
        $this->data = $data;
    }


    /**
     * @throws NoSuchEntityException
     */
    public function afterAvailableAddToCart(View $subject, bool $result): bool
    {
        if (!$this->session->isLoggedIn()) {
            return false;
        }
        $groupId = $this->session->getCustomer()->getGroupId();
        $excludedId = $this->data->getExcludedGroup($this->storeManager->getStore()->getId());
        $id = $this->data->group($this->storeManager->getStore()->getId());
        if ($groupId == $id || $groupId == $excludedId) {
            return true;
        }
        return false;
    }


}
