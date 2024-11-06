<?php

namespace Uzer\Middleware\Controller\Test;

use Magento\Customer\Model\CustomerFactory as CustomerModelFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Uzer\Core\Api\OrderIntegrationInterface;
use Uzer\Middleware\Logger\Logger;
use Magento\Sales\Model\ResourceModel\OrderFactory as ResourceModelFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Index implements HttpGetActionInterface
{
    protected JsonFactory $resultJsonFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected CollectionFactory $collectionFactory;
    protected OrderFactory $orderFactory;
    protected OrderIntegrationInterface $orderIntegration;
    protected Logger $logger;
    protected CustomerFactory $customerResourceFactory;
    protected CustomerModelFactory $customerFactory;

    /**
     * @param JsonFactory $resultJsonFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param CollectionFactory $collectionFactory
     * @param OrderFactory $orderFactory
     * @param OrderIntegrationInterface $orderIntegration
     * @param Logger $logger
     * @param CustomerFactory $customerResourceFactory
     * @param CustomerModelFactory $customerFactory
     */
    public function __construct(
        JsonFactory               $resultJsonFactory,
        ResourceModelFactory      $resourceModelFactory,
        CollectionFactory         $collectionFactory,
        OrderFactory              $orderFactory,
        OrderIntegrationInterface $orderIntegration,
        Logger                    $logger,
        CustomerFactory           $customerResourceFactory,
        CustomerModelFactory      $customerFactory
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->orderFactory = $orderFactory;
        $this->orderIntegration = $orderIntegration;
        $this->logger = $logger;
        $this->customerResourceFactory = $customerResourceFactory;
        $this->customerFactory = $customerFactory;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        /** @var Order $order */
        $order = $this->collectionFactory->create()->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', array('eq' => 365))
            ->load()->getFirstItem();
        $this->resourceModelFactory->create()->load($order, $order->getId());
        $customer = $this->customerFactory->create();
        $this->customerResourceFactory->create()->load($customer, $order->getCustomerId());
        $order->setCustomer($customer);
        $this->orderIntegration->save($order);
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(['result' => 'success', 'data' => $order->toArray()]);
        return $resultJson;
    }
}
