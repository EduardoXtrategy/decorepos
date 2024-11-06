<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Out of Stock Notification for Magento 2
 */

namespace Amasty\Xnotif\Model;

use Amasty\Base\Model\ConfigProviderAbstract;

class ConfigProvider extends ConfigProviderAbstract
{
    /**
     * @var string '{section}/'
     */
    protected $pathPrefix = 'amxnotif/';

    private const GDPR_ENABLED = 'gdrp/enabled';
    private const STOCK_CUSTOMER_GROUPS = 'stock/customer_group';
    private const PRICE_CUSTOMER_GROUPS = 'price/customer_group';

    private const XML_PATH_ERROR_TEMPLATE = 'catalog/productalert_cron/error_email_template';
    private const XML_PATH_ERROR_IDENTITY = 'catalog/productalert_cron/error_email_identity';
    private const XML_PATH_ERROR_RECIPIENT = 'catalog/productalert_cron/error_email';

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isGDPREnabled(?int $storeId = null): bool
    {
        return (bool)$this->getValue(self::GDPR_ENABLED, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return array
     */
    public function getAllowedStockCustomerGroups(?int $storeId = null): array
    {
        $allowedGroups = $this->getValue(self::STOCK_CUSTOMER_GROUPS, $storeId);

        return explode(',', $allowedGroups);
    }

    /**
     * @param int|null $storeId
     * @return array
     */
    public function getAllowedPriceCustomerGroups(?int $storeId = null): array
    {
        $allowedGroups = $this->getValue(self::PRICE_CUSTOMER_GROUPS, $storeId);

        return explode(',', $allowedGroups);
    }

    /**
     * @return string|null
     */
    public function getErrorTemplate(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ERROR_TEMPLATE);
    }

    /**
     * @return string|null
     */
    public function getErrorIdentity(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ERROR_IDENTITY);
    }

    /**
     * @return string|null
     */
    public function getErrorRecipient(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ERROR_RECIPIENT);
    }
}
