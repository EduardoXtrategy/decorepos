<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Out of Stock Notification for Magento 2
 */

namespace Amasty\Xnotif\Block\Catalog\Category;

use Magento\Framework\View\Element\Template;

class Config extends Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $encoder;

    /**
     * @var \Amasty\Xnotif\Helper\Config
     */
    private $config;

    public function __construct(
        \Magento\Framework\Json\EncoderInterface $encoder,
        \Magento\Framework\Registry $registry,
        \Amasty\Xnotif\Helper\Config $config,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->encoder = $encoder;
        $this->config = $config;
    }
    
    public function toHtml()
    {
        if ($this->isConfigurableProductsExist()) {
            return parent::toHtml();
        }
        
        return '';
    }

    /**
     * Check if popup on
     *
     * @return int
     */
    public function usePopupForSubscribe()
    {
        return (int) $this->config->isPopupForSubscribeEnabled();
    }

    /**
     * @return string
     */
    public function getConfigurableInfo()
    {
        return $this->encoder->encode([
            'ids' => $this->registry->registry('xnotifConfigurables'),
            'url' => $this->_urlBuilder->getUrl('xnotif/category/index')
        ]);
    }
    
    private function isConfigurableProductsExist(): bool
    {
        return (bool) $this->registry->registry('xnotifConfigurables');
    }
}
