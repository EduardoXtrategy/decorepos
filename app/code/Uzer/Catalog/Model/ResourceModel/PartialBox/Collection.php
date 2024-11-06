<?php

namespace Uzer\Catalog\Model\ResourceModel\PartialBox;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Uzer\Catalog\Model\PartialBox;
use Uzer\Catalog\Model\ResourceModel\PartialBox as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_quotes_partial_boxes';

    protected function _construct()
    {
        $this->_init(PartialBox::class, ResourceModel::class);
    }


}
