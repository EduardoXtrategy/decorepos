<?php

namespace Uzer\Samples\Controller\Kits;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Uzer\Samples\Helper\SendKitEmail;
use Uzer\Samples\Model\ResourceModel\SampleKitFactory as ResourceModelFactory;
use Uzer\Samples\Model\SampleKitFactory;

class Save implements HttpPostActionInterface
{

    protected Validator $formKeyValidator;
    protected RedirectFactory $redirectFactory;
    protected RequestInterface $request;
    protected ResourceModelFactory $resourceModel;
    protected SampleKitFactory $sampleKit;
    protected ManagerInterface $manager;
    protected SendKitEmail $sendKitEmail;

    /**
     * @param Validator $formKeyValidator
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param ResourceModelFactory $resourceModel
     * @param SampleKitFactory $sampleKit
     * @param ManagerInterface $manager
     * @param SendKitEmail $sendKitEmail
     */
    public function __construct(Validator $formKeyValidator, RedirectFactory $redirectFactory, RequestInterface $request, ResourceModelFactory $resourceModel, SampleKitFactory $sampleKit, ManagerInterface $manager, SendKitEmail $sendKitEmail)
    {
        $this->formKeyValidator = $formKeyValidator;
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
        $this->resourceModel = $resourceModel;
        $this->sampleKit = $sampleKit;
        $this->manager = $manager;
        $this->sendKitEmail = $sendKitEmail;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute()
    {
        $redirect = $this->redirectFactory->create();
        if ($this->formKeyValidator->validate($this->request)) {
            $data = $this->request->getParams();
            $sampleKit = $this->sampleKit->create()->addData($data);
            $this->resourceModel->create()->save($sampleKit);
            $this->sendKitEmail->sendEmail($sampleKit);
//            $this->manager->addSuccessMessage(__('Your sample kit request has been registered'));
            $redirect->setPath('samples/kits/success');
            return $redirect;
        }
        $redirect->setRefererUrl();
        return $redirect;
    }
}
