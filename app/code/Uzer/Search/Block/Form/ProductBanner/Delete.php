<?php

namespace Uzer\Search\Block\Form\ProductBanner;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Uzer\Search\Api\Data\ProductBannerInterface;

/**
 * Delete entity button.
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve Delete button settings.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return $this->wrapButtonSettings(
            'Delete',
            'delete',
            'deleteConfirm(\''
            . __('Are you sure you want to delete this productbanner?')
            . '\', \'' . $this->getUrl(
                '*/*/delete',
                [ProductBannerInterface::ENTITY_ID => $this->getEntityId()]
            ) . '\')',
            [],
            20
        );
    }
}
