<?php

namespace Uzer\Jobs\Controller\Form;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModel;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Debug implements HttpGetActionInterface
{
    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $this->debugResourceModel();
        //$this->debugRepository();
    }

    private function debugResourceModel()
    {
        $resourceModel = ObjectManager::getInstance()->create(ResourceModel::class);
        $customer = ObjectManager::getInstance()->create(Customer::class);
        $resourceModel->load($customer, 96);
        $dataMOdel = $customer->getDataModel();
        $dataMOdel->setCustomAttribute('company_data', 'Hp Hp Hp');
        $customer->updateData($dataMOdel);
        $customer->setCompanyData('Hp');
        $resourceModel->save($customer);
        $resourceModel->saveAttribute($customer, 'company_data');
        var_dump($customer);

    }

    private function debugRepository()
    {
        try {
            $customerRepository = ObjectManager::getInstance()->create(CustomerRepositoryInterface::class);
            $customer = $customerRepository->getById(96);
            $customer->setCustomAttribute('company_data', 'Pruebas hp');
            $customerRepository->save($customer);
            var_dump($customer);
        } catch (\Exception $ex) {
            var_dump($ex);
        }
    }
}
