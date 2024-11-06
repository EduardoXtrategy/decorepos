<?php

namespace Sensei\SortingPro\Plugin\Block;

use Sensei\SortingPro\Helper\Data;
use Magento\CatalogSearch\Block\Result as Subject;
use Magento\Framework\Registry;

class Result
{

    private $helper;

    private $registry;

    public function __construct(Data $helper, Registry $registry)
    {
        $this->helper = $helper;
        $this->registry = $registry;
    }

    public function afterSetListOrders(Subject $result)
    {
        $searchSortings = $this->helper->getSearchSorting();
        // getting first default sorting
        $sortBy = array_shift($searchSortings);
        $result->getListBlock()->setDefaultSortBy(
            $sortBy
        );
        $this->registry->unregister(Data::SEARCH_SORTING);
        $this->registry->register(Data::SEARCH_SORTING, true);

        return $this;
    }
}
