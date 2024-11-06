<?php

namespace Uzer\Samples\Controller\Address;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\ResourceModel\AddressFactory as ResourceModel;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NotFoundException;

class Edit implements HttpGetActionInterface
{
    /**
     * @var AddressRepositoryInterface
     */
    private AddressRepositoryInterface $addressRepository;
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    private AddressFactory $addressFactory;
    private ResourceModel $resourceModel;

    /**
     * @param AddressRepositoryInterface $addressRepository
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository,
        RequestInterface $request,
        ResultFactory $resultFactory,
        JsonFactory $resultJsonFactory,
        AddressFactory $addressFactory,
        ResourceModel $resourceModel
    ) {
        $this->addressRepository = $addressRepository;
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->addressFactory = $addressFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $id = $this->request->getParam('id');
        //$id = 40;

        $address = $this->addressFactory->create();
        $resourceModel = $this->resourceModel->create();
        $resourceModel->load($address, $id);

        $resultJson = $this->resultJsonFactory->create();

        return $resultJson ->setData(['address' => $address->toArray()]);

    }
}
