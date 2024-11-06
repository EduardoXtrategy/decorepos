<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResourceModel;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Uzer\Samples\Controller\Checkout\Complete;
use Uzer\Samples\Model\ResourceModel\SampleOrder;

class Teset implements HttpGetActionInterface
{

    public function execute()
    {
        $resourceModel = ObjectManager::getInstance()->create(SampleOrder::class);
        $sampleOrder = ObjectManager::getInstance()->create(\Uzer\Samples\Model\SampleOrder::class);
        $resourceModelCustomer = ObjectManager::getInstance()->create(CustomerResourceModel::class);
        $customer = ObjectManager::getInstance()->create(Customer::class);
        $resourceModel->load($sampleOrder, '1');
        $resourceModelCustomer->load($customer, 385);
//        var_dump($customer->getAttribute('company_data'));

        $resourceConnection = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\App\ResourceConnection::class);
        $table = $resourceConnection->getTableName('customer_entity_varchar');
        $connection = $resourceConnection->getConnection();
        $attributeId = $resourceModelCustomer->getAttribute('company_data')->getId();
        $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $customer->getId(), ['value']);
        if (count($result) > 0) {
            echo $result[0]['value'];
        }

        die();
    }
}
