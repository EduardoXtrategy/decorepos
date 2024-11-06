<?php

namespace Uzer\Blog\Model\ResourceModel\Post\Collection;

use Amasty\Rolepermissions\Helper\Data;

class Updater implements \Magento\Framework\View\Layout\Argument\UpdaterInterface
{

    private Data $ruleHelper;

    /**
     * @param Data $ruleHelper
     */
    public function __construct(Data $ruleHelper)
    {
        $this->ruleHelper = $ruleHelper;
    }


    /**
     * Update grid collection according to chosen order
     *
     * @param \Rokanthemes\Blog\Model\ResourceModel\Post\Collection $argument
     * @return \Rokanthemes\Blog\Model\ResourceModel\Post\Collection
     * @throws \Exception
     */
    public function update($argument)
    {
        throw new \Exception('Test');
//        $storeViews = $this->ruleHelper->currentRule()->getData('scope_storeviews');
//        $argument->addStoreFilter($storeViews);
//        return $argument;
    }
}
