<?php

namespace Uzer\Catalog\Plugin\Catalog\Pricing\Render;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Catalog\Pricing\Render\FinalPriceBox as CatalogFinalPriceBox;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\NoSuchEntityException;
use Uzer\Catalog\Helper\HidePriceData;
use Uzer\OnDemand\Model\OnDemandValidator;

class FinalPriceBox extends \Meetanshi\HidePrice\Plugin\Catalog\Pricing\Render\FinalPriceBox
{

    protected Session $session;
    protected MinimalPriceCalculatorInterface $minimalPriceCalculator;
    protected OnDemandValidator $onDemandValidator;
    protected Http $request;

    public function __construct(
        HidePriceData                   $helper,
        Session                         $customerSession,
        ProductFactory                  $productloader,
        MinimalPriceCalculatorInterface $minimalPriceCalculator,
        OnDemandValidator               $onDemandValidator,
        Session                         $session,
        Http                            $request
    )
    {
        parent::__construct($helper, $customerSession, $productloader);
        $this->session = $session;
        $this->minimalPriceCalculator = $minimalPriceCalculator;
        $this->request = $request;
        $this->onDemandValidator = $onDemandValidator;
    }


    /**
     * @param CatalogFinalPriceBox $subject
     * @param $result
     * @return mixed|string
     * @throws NoSuchEntityException
     */
    public function afterToHtml(CatalogFinalPriceBox $subject, $result)
    {
        $isOndemand = $this->onDemandValidator->isOndemand($subject->getSaleableItem());
        if ($isOndemand) {
            return '<span class="hide-price"></span>';
        }
        if ($this->helper->isEnabled() && !$isOndemand) {
            $selectedArea = $this->helper->getHidePriceArea();
            $productData = $this->catalogProduct->create()->load($subject->getSaleableItem()->getId());
            $enableHidePrice = $productData->getData('enable_hide_price');

            if ($selectedArea == 'category') {
                if (!$this->helper->isAllowCustomerGroups()) {
                    $showInCategory = $this->helper->showPrdCategories($subject->getSaleableItem()->getId());
                    if ($showInCategory) {
                        if ($subject->getPrice()->getPriceCode() == "tier_price") {
                            return '';
                        } else {
                            return $this->getHidePriceText();
                        }
                    } else {
                        return $result;
                    }
                } elseif ($this->helper->isAllowCustomerGroups()) {
                    $showInCategory = $this->helper->showPrdCategories($subject->getSaleableItem()->getId());
                    $customerGroupIds = $this->helper->showCustomerGroups();
                    if ($showInCategory && $customerGroupIds) {
                        if ($subject->getPrice()->getPriceCode() == "tier_price") {
                            return '';
                        } else {
                            return $this->getHidePriceText();
                        }
                    } else {
                        return $result;
                    }
                }
            } elseif ($selectedArea == 'product') {
                if ($enableHidePrice && !$this->helper->isAllowCustomerGroups()) {
                    if ($subject->getSaleableItem()->getId()) {
                        if ($subject->getPrice()->getPriceCode() == "tier_price") {
                            return '';
                        } else {
                            return $this->getHidePriceText();
                        }
                    } else {
                        return $result;
                    }
                } elseif ($enableHidePrice && $this->helper->isAllowCustomerGroups()) {
                    $customerGroupIds = $this->helper->showCustomerGroups();
                    if ($subject->getSaleableItem()->getId() && $customerGroupIds) {
                        if ($subject->getPrice()->getPriceCode() == "tier_price") {
                            return '';
                        } else {
                            return $this->getHidePriceText();
                        }
                    } else {
                        return $result;
                    }
                } else {
                    return $result;
                }
            } else {
                if ($this->helper->isAllowCustomerGroups()) {
                    $customerGroupIds = $this->helper->showCustomerGroups();
                    if ($customerGroupIds) {
                        if ($subject->getPrice()->getPriceCode() == "tier_price") {
                            return '';
                        } else {
                            return $this->getHidePriceText();
                        }
                    } else {
                        return $result;
                    }
                } else {
                    if ($subject->getPrice()->getPriceCode() == "tier_price") {
                        return '';
                    } else {
                        return $this->getHidePriceText();
                    }
                }
            }
        }
        return $result;
    }


    /**
     * Render minimal amount
     *
     * @param CatalogFinalPriceBox $subject
     * @param $result
     * @return string
     */
    public function afterRenderAmountMinimal(CatalogFinalPriceBox $subject, $result): string
    {
        $id = $subject->getPriceId() ? $subject->getPriceId() : 'product-minimal-price-' . $subject->getSaleableItem()->getId();

        $amount = $this->minimalPriceCalculator->getAmount($subject->getSaleableItem());
        if ($amount === null) {
            return '';
        }
        return $subject->renderAmount(
            $amount,
            [
                'display_label' => __('From'),
                'price_id' => $id,
                'include_container' => false,
                'skip_adjustments' => true
            ]
        );
    }

    public function getHidePriceText()
    {
        if ($this->request->getFullActionName() == 'catalog_product_view') {
            if ($this->session->isLoggedIn()) {
                return $this->helper->getHidePriceText();
            } else {
                return $this->helper->getHidePriceTextForNotLogged();
            }
        }
        return '<span class="hide-price"></span>';
    }

}
