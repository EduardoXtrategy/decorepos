<?php

namespace Uzer\Customer\Model\ResourceModel\InformationBusiness;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Customer\Model\InformationBusiness as Model;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customers_information_business_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
