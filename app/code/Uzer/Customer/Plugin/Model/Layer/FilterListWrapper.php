<?php

namespace Uzer\Customer\Plugin\Model\Layer;

use Amasty\Shopby\Model\Layer\FilterList;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SessionException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Customer\Helper\Data;

class FilterListWrapper
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
     * @param FilterList $subject
     * @param AbstractFilter[] $result
     * @return array|AbstractFilter[]
     * @throws NoSuchEntityException
     * @throws SessionException
     * @throws LocalizedException
     */
    public function afterGetFilters(FilterList $subject, array $result): array
    {
        if (!$this->session->isLoggedIn()) {
            return $this->removePrice($subject, $result);
        }
        $customerGroupId = $this->session->getCustomerGroupId();
        $customerGroup = $this->data->group($this->storeManager->getStore()->getId());
        $customerGroupExcluded = $this->data->getExcludedGroup($this->storeManager->getStore()->getId());
        if ($customerGroupId != $customerGroup && $customerGroupId != $customerGroupExcluded) {
            return $this->removePrice($subject, $result);
        }
        return $result;
    }

    /**
     * @param FilterList $subject
     * @param AbstractFilter[] $result
     * @return AbstractFilter[]
     */
    public function removePrice(FilterList $subject, array $result): array
    {
        foreach ($result as $key => $item) {
            if ($item->getRequestVar() == 'price') {
                unset($result[$key]);
                break;
            }
        }
        return $result;
    }

}
