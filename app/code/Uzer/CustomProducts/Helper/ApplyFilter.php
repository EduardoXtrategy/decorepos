<?php

namespace Uzer\CustomProducts\Helper;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResourceConnection as AppResource;

class ApplyFilter
{


    protected Http $request;
    protected Session $session;
    private AppResource $resource;
    private bool $exists = false;

    /**
     * @param Http $request
     * @param Session $session
     * @param AppResource $resource
     */
    public function __construct(Http $request, Session $session, AppResource $resource)
    {
        $this->request = $request;
        $this->session = $session;
        $this->resource = $resource;
    }


    public function apply(Collection $productCollection): Collection
    {
        $productCollection->addAttributeToFilter('custom', array('eq' => 0));
        return $productCollection;
    }

    /**
     * @return Http
     */
    public function getRequest(): Http
    {
        return $this->request;
    }


}
