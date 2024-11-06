<?php

namespace Uzer\OnDemand\Controller\Add;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\OnDemand\Model\OnDemandRequestFactory;
use Uzer\OnDemand\Model\ResourceModel\OnDemandRequest as ResourceModel;
use Uzer\OnDemand\Model\SendEmail;

class Ajax implements HttpPostActionInterface
{
    protected JsonFactory $jsonFactory;
    protected RequestInterface $request;
    protected Validator $validator;
    protected ManagerInterface $messageManager;
    protected StoreManagerInterface $storeManagement;
    protected ResourceModel $resourceModel;
    protected OnDemandRequestFactory $demandRequestFactory;
    protected SendEmail $sendEmail;
    protected Session $customerSession;

    /**
     * @param JsonFactory $jsonFactory
     * @param RequestInterface $request
     * @param Validator $validator
     * @param ManagerInterface $messageManager
     * @param StoreManagerInterface $storeManagement
     * @param ResourceModel $resourceModel
     * @param OnDemandRequestFactory $demandRequestFactory
     * @param SendEmail $sendEmail
     * @param Session $customerSession
     */
    public function __construct(
        JsonFactory            $jsonFactory,
        RequestInterface       $request,
        Validator              $validator,
        ManagerInterface       $messageManager,
        StoreManagerInterface  $storeManagement,
        ResourceModel          $resourceModel,
        OnDemandRequestFactory $demandRequestFactory,
        SendEmail              $sendEmail,
        Session                $customerSession
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->request = $request;
        $this->validator = $validator;
        $this->messageManager = $messageManager;
        $this->storeManagement = $storeManagement;
        $this->resourceModel = $resourceModel;
        $this->demandRequestFactory = $demandRequestFactory;
        $this->sendEmail = $sendEmail;
        $this->customerSession = $customerSession;
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        if (!$this->validator->validate($this->request)) {
            return $resultJson->setData(array('result' => 'error', 'message' => __('Invalid form, please refresh page')));
        }
        $onDemandRequest = $this->demandRequestFactory->create();
        $onDemandRequest->addData($this->request->getParams());
        $onDemandRequest->setStoreId($this->storeManagement->getStore()->getId());
        $onDemandRequest->setCustomersId($this->customerSession->getCustomerId());
        $this->resourceModel->save($onDemandRequest);
        $this->sendEmail->sendEmail($onDemandRequest);
        return $resultJson->setData(array('result' => 'success', 'model' => $onDemandRequest->toArray()));
    }
}
