<?php

namespace Uzer\Jobs\Controller\Form;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\HTTP\Header;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Message\ManagerInterface;
use \Magento\Store\Model\StoreManagerInterface;
use Uzer\Jobs\Helper\SendEmail;
use Uzer\Jobs\Model\RequestJob;
use Uzer\Jobs\Model\RequestJobFactory;
use Uzer\Jobs\Model\ResourceModel\RequestJobFactory as ResourceModelFactory;

class Save implements HttpPostActionInterface
{

    private RequestJobFactory $requestJobFactory;
    private ResourceModelFactory $resourceModel;
    private Validator $formKeyValidator;
    private RequestInterface $request;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private RemoteAddress $remoteAddress;
    private Header $httpHeader;
    private StoreManagerInterface $storeManager;
    private RequestJob $requestJob;
    private SendEmail $sendEmail;

    /**
     * Save constructor.
     * @param Validator $formKeyValidator
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     * @param RemoteAddress $remoteAddress
     * @param Header $httpHeader
     * @param RequestJobFactory $requestJobFactory
     * @param ResourceModelFactory $resourceModel
     * @param StoreManagerInterface $storeManager
     * @param SendEmail $sendEmail
     */
    public function __construct(
        Validator             $formKeyValidator,
        RequestInterface      $request,
        RedirectFactory       $redirectFactory,
        ManagerInterface      $messageManager,
        RemoteAddress         $remoteAddress,
        Header                $httpHeader,
        RequestJobFactory     $requestJobFactory,
        ResourceModelFactory  $resourceModel,
        StoreManagerInterface $storeManager,
        SendEmail             $sendEmail
    )
    {
        $this->formKeyValidator = $formKeyValidator;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
        $this->requestJobFactory = $requestJobFactory;
        $this->resourceModel = $resourceModel;
        $this->storeManager = $storeManager;
        $this->sendEmail = $sendEmail;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $redirect = $this->redirectFactory->create();
        if ($this->formKeyValidator->validate($this->request)) {
            try {
                $this->save();
                /** @var \Laminas\Stdlib\Parameters $files */
                $files = $this->request->getFiles();
                $file = $files->get('document');
                if ($file) {
                    $fileContent = file_get_contents($file['tmp_name']);
                    $fileName = $file['name'];
                    $type = $file['type'];
                    $this->sendEmail->sendEmail($this->requestJob, $fileContent, $fileName, $type);
                } else {
                    $this->sendEmail->sendEmail($this->requestJob);
                }
                $redirect->setPath('jobs/form/success');
                return $redirect;
            } catch (AlreadyExistsException | NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('An error has occurred, please try again'));
                $redirect->setRefererUrl();
                return $redirect;
            }
        }
        $this->messageManager->addErrorMessage(__('Invalid form, please try again'));
        $redirect->setRefererUrl();
        return $redirect;
    }

    /**
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save()
    {
        $this->requestJob = $this->requestJobFactory->create();
        $this->requestJob->addData($this->request->getParams());
        $this->requestJob->setStoreId($this->storeManager->getStore()->getId());
        $this->resourceModel->create()->save($this->requestJob);
    }
}
