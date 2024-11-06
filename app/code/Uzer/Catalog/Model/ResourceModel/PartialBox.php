<?php

namespace Uzer\Catalog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PartialBox extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_quotes_partial_boxes';

    protected function _construct()
    {
        $this->_init('uzer_quotes_partial_boxes', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
