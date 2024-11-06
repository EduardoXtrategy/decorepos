<?php

namespace Uzer\Checkoutstep\Plugin\Checkout;

use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class LayoutProcessor
{

    protected StoreManagerInterface $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }


    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     * @throws LocalizedException
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout): array
    {
        if ($this->storeManager->getWebsite()->getId() == 3) {
            $jsLayout = $this->sortEu($subject, $jsLayout);
        } else {
            $jsLayout = $this->sortUsa($subject, $jsLayout);
        }
        return $jsLayout;
    }

    public function sortUsa(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout): array
    {
        $jsLayout = $this->setPosition($jsLayout, 'firstname', 10);
        $jsLayout = $this->setPosition($jsLayout, 'lastname', 20);
        $jsLayout = $this->setPosition($jsLayout, 'company', 30);
        $jsLayout = $this->setPosition($jsLayout, 'country_id', 40);
        $jsLayout = $this->setPosition($jsLayout, 'street', 50);
        $jsLayout = $this->setPosition($jsLayout, 'city', 60);
        $jsLayout = $this->setPosition($jsLayout, 'region_id', 70);
        $jsLayout = $this->setPosition($jsLayout, 'postcode', 80);
        $jsLayout = $this->setPosition($jsLayout, 'telephone', 90);
        return $jsLayout;
    }

    public function sortEu(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout): array
    {
        $jsLayout = $this->setPosition($jsLayout, 'firstname', 10);
        $jsLayout = $this->setPosition($jsLayout, 'lastname', 20);
        $jsLayout = $this->setPosition($jsLayout, 'company', 30);
        $jsLayout = $this->setPosition($jsLayout, 'street', 40);
        $jsLayout = $this->setPosition($jsLayout, 'postcode', 50);
        $jsLayout = $this->setPosition($jsLayout, 'city', 60);
        $jsLayout = $this->setPosition($jsLayout, 'country_id', 70);
//        $jsLayout = $this->setPosition($jsLayout, 'region_id', 70);
        $jsLayout = $this->setPosition($jsLayout, 'telephone', 80);
        return $jsLayout;
    }

    public function setPosition(array $jsLayout, string $code, int $position): array
    {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$code] ['sortOrder'] = $position;
        return $jsLayout;
    }

}
