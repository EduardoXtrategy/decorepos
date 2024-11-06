<?php

namespace Rokanthemes\Blog\Block\Adminhtml\Widget;

use Amasty\Rolepermissions\Helper\Data;

class Grid extends \Magento\Backend\Block\Widget\Grid
{
    private Data $ruleHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data            $backendHelper,
        Data                                    $ruleHelper,
        array                                   $data = []
    )
    {
        parent::__construct($context, $backendHelper, $data);
        $this->ruleHelper = $ruleHelper;
    }

    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        /** @var \Rokanthemes\Blog\Model\ResourceModel\Post\Collection $collection */
        $collection = $this->getCollection();
        $storeViews = $this->ruleHelper->currentRule()->getData('scope_storeviews');
        if ($storeViews) {
            $collection->addStoreFilter($storeViews);
        }
        return $this;
    }

    public function getPreparedCollection()
    {
        /** @var \Rokanthemes\Blog\Model\ResourceModel\Post\Collection $collection */
        return parent::getPreparedCollection();
    }

}
