<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Store Pickup for Magento 2
 */

namespace Amasty\StorePickup\Block\Adminhtml\Methods\Edit\Tab\Rates;

class Grid extends \Magento\Backend\Block\Widget\Grid
{
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        array $data
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $backendHelper, $data);

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        /**
         * @var \Amasty\StorePickup\Model\ResourceModel\Rate\Collection $collection
         */
        $collection = $om->create(\Amasty\StorePickup\Model\ResourceModel\Rate\Collection::class);
        $id = $this->getRequest()->getParam('id');
        $collection->addFieldToFilter('method_id', $id);
        $this->setCollection($collection);
        $this->setData('use_ajax', true);
    }
}
