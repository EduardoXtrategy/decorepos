<?php

namespace Uzer\Samples\Block\Form\SamplesCart;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

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
            . __('Are you sure you want to delete this samplescart?')
            . '\', \'' . $this->getUrl('*/*/delete', ['entity_id' => $this->getEntityId()]) . '\')',
            [],
            20
        );
    }
}
