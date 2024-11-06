<?php

namespace Uzer\Blog\Block\Widget;

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


    public function getPreparedCollection()
    {
        /** @var \Rokanthemes\Blog\Model\ResourceModel\Post\Collection $collection */
        $collection = parent::getPreparedCollection();
        $storeViews = $this->ruleHelper->currentRule()->getData('scope_storeviews');
        var_dump($storeViews);
        die();
        if ($storeViews) {
            $collection->addStoreFilter($storeViews);
        }
        return $collection;
    }

}
