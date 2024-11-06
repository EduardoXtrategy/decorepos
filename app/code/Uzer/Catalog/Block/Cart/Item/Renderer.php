<?php

namespace Uzer\Catalog\Block\Cart\Item;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;
use Magento\Checkout\Model\Session;
use Magento\ConfigurableProduct\Block\Cart\Item\Renderer\Configurable;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Module\Manager;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;
use Magento\Framework\View\Element\Template\Context;
use Uzer\Catalog\Helper\Data as HelperData;

class Renderer extends Configurable
{

    protected HelperData $helperData;

    public function __construct(
        Context                         $context,
        Configuration                   $productConfig,
        Session                         $checkoutSession,
        ImageBuilder                    $imageBuilder, Data $urlHelper,
        ManagerInterface                $messageManager, PriceCurrencyInterface $priceCurrency,
        Manager                         $moduleManager,
        InterpretationStrategyInterface $messageInterpretationStrategy,
        HelperData                      $helperData,
        array                           $data = [],
        ItemResolverInterface           $itemResolver = null
    )
    {
        parent::__construct(
            $context,
            $productConfig,
            $checkoutSession,
            $imageBuilder,
            $urlHelper,
            $messageManager,
            $priceCurrency,
            $moduleManager,
            $messageInterpretationStrategy,
            $data,
            $itemResolver
        );
        $this->helperData = $helperData;
    }

    public function getTemplate(): string
    {
//        $enabled = $this->helperData->isEnable($this->_storeManager->getStore()->getId());
        $enabled = false;
        if ($enabled) {
            return 'Uzer_Catalog::cart/item/default.phtml';
        }
        return parent::getTemplate();
    }


}
