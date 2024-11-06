<?php


namespace Uzer\Customer\Observer;


use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModel;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;

class CustomerRegisterObserver implements ObserverInterface
{

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected CustomerFactory $_customerFactory;
    protected ResourceModel $resourceModel;
    private RequestInterface $request;

    function __construct(CustomerFactory $customerFactory, ResourceModel $resourceModel, RequestInterface $request)
    {
        $this->_customerFactory = $customerFactory;
        $this->resourceModel = $resourceModel;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     * @return int
     * @throws AlreadyExistsException
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Data\Customer $customerData */
        $customerData = $observer->getCustomer();
        $customer = $this->_customerFactory->create();
        $resourceModel = $this->resourceModel->create();
        $resourceModel->load($customer, $customerData->getId());
        $isSave = false;
        if ($this->request->getParam('company')) {
            $isSave = true;
            $customer->setCompanyData($this->request->getParam('company'));
        }
        if ($this->request->getParam('title')) {
            $isSave = true;
            $customer->setTitleData($this->request->getParam('title'));
        }
        if ($this->request->getParam('phone')) {
            $isSave = true;
            $customer->setPhone($this->request->getParam('phone'));
        }
        if ($isSave) {
            $resourceModel->save($customer);
            $resourceModel->saveAttribute($customer, 'company_data');
            $resourceModel->saveAttribute($customer, 'title_data');
            $resourceModel->saveAttribute($customer, 'phone');
        }
        return 0;
    }
}
