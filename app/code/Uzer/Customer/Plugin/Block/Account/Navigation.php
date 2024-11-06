<?php

namespace Uzer\Customer\Plugin\Block\Account;

use Magento\Customer\Model\Session;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModel;
use Magento\Customer\Model\CustomerFactory as Factory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Customer\Helper\Data;

class Navigation
{

    protected Session $session;
    protected ResourceModel $resourceModel;
    protected Factory $factory;
    protected Customer $customer;
    protected Data $helperData;
    protected StoreManagerInterface $storeManager;

    /**
     * @param Session $session
     * @param ResourceModel $resourceModel
     * @param Factory $factory
     * @param Customer $customer
     * @param Data $helperData
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Session               $session,
        ResourceModel         $resourceModel,
        Factory               $factory,
        Customer              $customer,
        Data                  $helperData,
        StoreManagerInterface $storeManager
    )
    {
        $this->session = $session;
        $this->resourceModel = $resourceModel;
        $this->factory = $factory;
        $this->customer = $customer;
        $this->helperData = $helperData;
        $this->storeManager = $storeManager;
    }


    /**
     * @throws NoSuchEntityException
     */
    public function afterGetLinks(\Magento\Customer\Block\Account\Navigation $navigation, array $result): array
    {
        $this->customer = $this->factory->create();
        $this->resourceModel->create()->load($this->customer, $this->session->getCustomerId());
        $group = $this->helperData->group($this->storeManager->getStore()->getId());
        $excludedGroup = $this->helperData->getExcludedGroup($this->storeManager->getStore()->getId());
        /** @var \Magento\Customer\Block\Account\SortLink $item */
        foreach ($result as $key => $item) {
            if ($item->getNameInLayout() == 'customer-account-navigation-online-access-link') {
                if ($this->customer->getGroupId() == $group || $this->customer->getGroupId() == $excludedGroup) {
                    unset($result[$key]);
                }
            }
            if ($item->getNameInLayout() == 'customer-account-navigation-orders-link') {
                if ($this->customer->getGroupId() != $group && $this->customer->getGroupId() != $excludedGroup) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

}
