<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Block\Adminhtml\Rates;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_rates';
        $this->_blockGroup = 'Amasty_StorePickup';

        $objectRate = $this->_coreRegistry->registry('amasty_storepick_rate');

        parent::_construct();

        $backUrl = $this->getUrl(
            'amstorepick/methods/edit/',
            [
                'id' => $objectRate->getMethodId(),
                'tab' => 'rates_section'
            ]
        );

        $this->buttonList->add(
            'my_back',
            [
                'class' => 'back',
                'label' => __('Back'),
                'onclick' => 'setLocation(\'' . $backUrl . '\')'
            ],
            0,
            0
        );

        $this->buttonList->add(
            'save_and_continue_edit',
            [
                'class' => 'save',
                'label' => __('Save and Add New Store'),
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#edit_form']],
                ]
            ],
            10
        );

        $deleteUrl = $this->getUrl('amstorepick/rates/delete/', ['id' => $objectRate->getId()]);

        $this->buttonList->add(
            'my_delete',
            [
                'class' => 'delete',
                'label' => __('Delete'),
                'onclick' => 'deleteConfirm(\'' . __('Are you sure?') . '\',\'' . $deleteUrl . '\')'
            ],
            20,
            20
        );
        $this->addButton(
            'my_save',
            [
                'label' => __('Save'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'save',
                            'target' => '#edit_form',
                            'eventData' => ['action' => ['args' => ['to_method' => true]]],
                        ],
                    ],
                ]
            ],
            30,
            30
        );

        $this->buttonList->remove('back');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('save');
    }

    public function getHeaderText()
    {
        return __("Edit Rate");
    }
}
