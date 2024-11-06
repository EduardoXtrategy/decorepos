<?php

namespace Uzer\OnDemand\Controller\Add;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\OnDemand\Model\OnDemandRequestFactory;
use Uzer\OnDemand\Model\ResourceModel\OnDemandRequest as ResourceModel;
use Uzer\OnDemand\Model\SendEmail;

class Index implements HttpPostActionInterface
{

    protected RequestInterface $request;
    protected Validator $validator;
    protected ManagerInterface $messageManager;
    protected StoreManagerInterface $storeManagement;
    protected ResourceModel $resourceModel;
    protected OnDemandRequestFactory $demandRequestFactory;
    protected SendEmail $sendEmail;
    protected ResultFactory $resultFactory;
    protected Session $customerSession;

    /**
     * @param RequestInterface $request
     * @param Validator $validator
     * @param ManagerInterface $messageManager
     * @param StoreManagerInterface $storeManagement
     * @param ResourceModel $resourceModel
     * @param OnDemandRequestFactory $demandRequestFactory
     * @param SendEmail $sendEmail
     * @param ResultFactory $resultFactory
     * @param Session $customerSession
     */
    public function __construct(
        RequestInterface       $request,
        Validator              $validator,
        ManagerInterface       $messageManager,
        StoreManagerInterface  $storeManagement,
        ResourceModel          $resourceModel,
        OnDemandRequestFactory $demandRequestFactory,
        SendEmail              $sendEmail,
        ResultFactory          $resultFactory,
        Session                $customerSession
    )
    {
        $this->request = $request;
        $this->validator = $validator;
        $this->messageManager = $messageManager;
        $this->storeManagement = $storeManagement;
        $this->resourceModel = $resourceModel;
        $this->demandRequestFactory = $demandRequestFactory;
        $this->sendEmail = $sendEmail;
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute()
    {
        if (!$this->validator->validate($this->request)) {
            $this->messageManager->addErrorMessage(__('Invalid form, please refresh page'));
        }
        $onDemandRequest = $this->demandRequestFactory->create();
        $onDemandRequest->addData($this->request->getParams());
        $onDemandRequest->setStoreId($this->storeManagement->getStore()->getId());
        $onDemandRequest->setCustomersId($this->customerSession->getCustomerId());
        $this->resourceModel->save($onDemandRequest);
        $this->sendEmail->sendEmail($onDemandRequest);
        $this->messageManager->addSuccessMessage(__('Your request has been made'));
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setRefererUrl();
        return $redirect;
    }
}
