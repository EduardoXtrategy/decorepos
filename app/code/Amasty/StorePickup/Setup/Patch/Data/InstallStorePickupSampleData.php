<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Setup\Patch\Data;

use Amasty\StorePickup\Model\Sample;
use Magento\Framework\Module\ResourceInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InstallStorePickupSampleData implements DataPatchInterface
{
    /**
     * @var ResourceInterface
     */
    private $moduleResource;

    /**
     * @var Sample
     */
    private $installer;

    public function __construct(
        ResourceInterface $moduleResource,
        Sample $installer
    ) {
        $this->moduleResource = $moduleResource;
        $this->installer = $installer;
    }

    public function apply()
    {
        $setupDataVersion = $this->moduleResource->getDataVersion('Amasty_StorePickup');

        // Check if module was already installed or not.
        // If setup_version present in DB then we don't need to install fixtures, because setup_version is a marker.
        if (!$setupDataVersion) {
            $this->installer->install();
        }
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
