<?php

namespace Uzer\Customer\Plugin\Helper;

use Magento\Customer\Model\Customer;
use Uzer\Customer\Api\Data\InformationBusinessInterface;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModel;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\ResourceConnection;

class SendEmailWrapper
{

    protected ResourceModel $resourceModel;
    protected CustomerFactory $customerFactory;
    protected ResourceConnection $resourceConnection;

    public function __construct(
        ResourceModel $resourceModel,
        CustomerFactory $customerFactory,
        ResourceConnection $resourceConnection
    ) {
        $this->resourceModel = $resourceModel;
        $this->customerFactory = $customerFactory;
        $this->resourceConnection = $resourceConnection;
    }


    public function afterGetTemplateVars(\Uzer\Customer\Helper\SendEmail $subject, array $result, InformationBusinessInterface $informationBusiness)
    {
        /** @var Customer $customer */
        $customer = $this->customerFactory->create();
        $this->resourceModel->create()->load($customer, $informationBusiness->getCustomersId());
        $company = $this->getCompany($customer);
        $result['company'] = $company;
        return $result;
    }

    public function getCompany(Customer $customer)
    {
        $resourceConnection = $this->resourceConnection;
        $table = $resourceConnection->getTableName('customer_entity_varchar');
        $connection = $resourceConnection->getConnection();
        $attributeId = $this->resourceModel->create()->getAttribute('company_data')->getId();
        $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $customer->getId(), ['value']);
        if (count($result) > 0) {
            return $result[0]['value'];
        }
        return '';
    }
}
