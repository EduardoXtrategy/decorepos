<?php

namespace Uzer\Customer\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Uzer\Customer\Model\InformationBusiness as Model;

class InformationBusiness extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customers_information_business_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('customers_information_business', 'entity_id');
        $this->_useIsObjectNew = true;
    }


    /**
     * @throws LocalizedException
     */
    public function loadByCustomerId(Model $informationBusiness, int $customerId): InformationBusiness
    {
        $connection = $this->getConnection();
        $bind = ['customers_id' => $customerId];
        $select = $connection->select()->from(
            $this->getMainTable(),
            [$this->getIdFieldName()]
        )->where(
            'customers_id = :customers_id'
        );
        $customerId = $connection->fetchOne($select, $bind);
        if ($customerId) {
            $this->load($informationBusiness, $customerId);
        } else {
            $informationBusiness->setData([]);
        }

        return $this;
    }
}
