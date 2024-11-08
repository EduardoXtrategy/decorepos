<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Mostviewed
 */


namespace Amasty\Mostviewed\Model\ResourceModel\Analytics;

use Amasty\Mostviewed\Api\Data\ClickInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Click extends AbstractDb
{
    /**
     * ResourceModel initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ClickInterface::MAIN_TABLE, ClickInterface::ID);
    }
}
