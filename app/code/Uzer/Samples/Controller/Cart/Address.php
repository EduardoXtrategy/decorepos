<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Uzer\Samples\Model\ResourceModel\SamplesCartFactory;
use Uzer\Samples\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;

class Address implements HttpPostActionInterface
{

    protected Session $session;
    protected SamplesCartFactory $samplesCartFactory;
    protected JsonFactory $resultJsonFactory;
    protected RequestInterface $request;

    /**
     * @param Session $session
     * @param SamplesCartFactory $samplesCartFactory
     * @param JsonFactory $resultJsonFactory
     * @param RequestInterface $request
     */
    public function __construct(Session $session, SamplesCartFactory $samplesCartFactory, JsonFactory $resultJsonFactory, RequestInterface $request)
    {
        $this->session = $session;
        $this->samplesCartFactory = $samplesCartFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $samplesCart = $this->session->getSamplesCart();
        $samplesCart->setCustomerAddressId($this->request->getParam('customer_address_id'));
        $this->samplesCartFactory->create()->save($samplesCart);
        return $resultJson->setData(['samples_cart' => $samplesCart->toArray()]);
    }
}
