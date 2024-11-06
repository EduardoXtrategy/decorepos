<?php

namespace Uzer\Customer\Model;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModel;
use Magento\Framework\App\ResourceConnection;

class CustomerCustomInfo
{

    protected ResourceModel $resourceModel;
    protected ResourceConnection $resourceConnection;

    /**
     * @param ResourceModel $resourceModel
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceModel $resourceModel, ResourceConnection $resourceConnection)
    {
        $this->resourceModel = $resourceModel;
        $this->resourceConnection = $resourceConnection;
    }


    public function get(Customer $customer, string $code)
    {
        $resourceConnection = $this->resourceConnection;
        $table = $resourceConnection->getTableName('customer_entity_varchar');
        $connection = $resourceConnection->getConnection();
        $attributeId = $this->resourceModel->create()->getAttribute($code)->getId();
        $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $customer->getId(), ['value']);
        if (count($result) > 0) {
            return $result[0]['value'];
        }
        return '';
    }

    public function getByData(\Magento\Customer\Model\Data\Customer $customer, string $code)
    {
        $resourceConnection = $this->resourceConnection;
        $table = $resourceConnection->getTableName('customer_entity_varchar');
        $connection = $resourceConnection->getConnection();
        $attributeId = $this->resourceModel->create()->getAttribute($code)->getId();
        $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $customer->getId(), ['value']);
        if (count($result) > 0) {
            return $result[0]['value'];
        }
        return '';
    }

    public function getByCustomerId($customerId, string $code)
    {
        $resourceConnection = $this->resourceConnection;
        $table = $resourceConnection->getTableName('customer_entity_varchar');
        $connection = $resourceConnection->getConnection();
        $attributeId = $this->resourceModel->create()->getAttribute($code)->getId();
        $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $customerId, ['value']);
        if (count($result) > 0) {
            return $result[0]['value'];
        }
        return '';
    }

}
