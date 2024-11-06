<?php

namespace Sensei\SortingPro\Plugin\Catalog\Helper\Product;

use Sensei\SortingPro\Helper\Data;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Helper\Product\ProductList as Subject;

class ProductList
{
    private $helper;

    private $request;

    private $searchModules = [
        'catalogsearch'
    ];

    public function __construct(
        Data $helper,
        RequestInterface $request
    ) {
        $this->helper = $helper;
        $this->request = $request;
    }

    public function afterGetDefaultSortField(Subject $subject, $sortBy)
    {
        if (in_array($this->request->getModuleName(), $this->searchModules)) {
            $searchSortings = $this->helper->getSearchSorting();
            // getting first default sorting
            $sortBy = array_shift($searchSortings);
        }

        return $sortBy;
    }
}
