<?php

namespace Uzer\Contact\Controller\Form;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\HTTP\Header;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Message\ManagerInterface;
use Uzer\Contact\Helper\Data;
use Uzer\Contact\Model\ContactFormFactory as ModelFactory;
use Uzer\Contact\Model\ResourceModel\ContactFormFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

class Save implements HttpPostActionInterface
{


    private ContactFormFactory $resourceModelFactory;
    private ModelFactory $modelFactory;
    private Validator $formKeyValidator;
    private RequestInterface $request;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $messageManager;
    private RemoteAddress $remoteAddress;
    private Header $httpHeader;
    protected TransportBuilder $transportBuilder;
    protected StoreManagerInterface $storeManager;
    protected StateInterface $inlineTranslation;
    protected Data $data;

    /**
     * Save constructor.
     * @param ContactFormFactory $resourceModelFactory
     * @param ModelFactory $modelFactory
     * @param Validator $formKeyValidator
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     * @param Context $context
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $state
     * @param RemoteAddress $remoteAddress
     * @param Header $httpHeader
     * @param Data $data
     */
    public function __construct(
        ContactFormFactory    $resourceModelFactory,
        ModelFactory          $modelFactory,
        Validator             $formKeyValidator,
        RequestInterface      $request,
        RedirectFactory       $redirectFactory,
        ManagerInterface      $messageManager,
        Context               $context,
        TransportBuilder      $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface        $state,
        RemoteAddress         $remoteAddress,
        Header                $httpHeader,
        Data                  $data
    )
    {
        $this->resourceModelFactory = $resourceModelFactory;
        $this->modelFactory = $modelFactory;
        $this->formKeyValidator = $formKeyValidator;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->remoteAddress = $remoteAddress;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        $this->httpHeader = $httpHeader;
        $this->data = $data;
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
        if (!$this->validateCaptcha()) {
            $redirect->setRefererUrl();
            $this->messageManager->addWarningMessage(__('Incorrect captcha'));
            return $redirect;
        }
        if (!$this->formKeyValidator->validate($this->request)) {
            $redirect->setRefererUrl();
            $this->messageManager->addWarningMessage(__('Invalid form key, please refresh page'));
            return $redirect;
        }
        $model = $this->modelFactory->create();
        $data = $this->request->getParams();
        unset($data['form_key']);
        unset($data['terms']);
        foreach ($data as $key => $value) {
            $model->setData($key, $value);
        }
        $model->setIp($this->remoteAddress->getRemoteAddress());
        $model->setUserAgent($this->httpHeader->getHttpUserAgent());
        $model->setStoreId($this->storeManager->getStore()->getId());
        $resourceModel = $this->resourceModelFactory->create();
        $resourceModel->save($model);
        $this->sendEmail($data);

        $redirect->setPath('contact_form/form/success');

        return $redirect;
    }


    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendEmail($data)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
        $emailFrom = $scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        try {
            $sender = [
                'name' => 'Decowraps',
                'email' => $emailFrom
            ];
            $vars = [
                'full_name' => $data['full_name'],
                'company' => $data['company'],
                'phone' => $data['phone'],
                'message' => $data['message'],
                'email' => $data['email']
            ];
            $this->inlineTranslation->suspend();
            $templateId = $this->data->emailTemplateId($this->storeManager->getStore()->getId());
            $transportBuilder = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($this->getTemplateOptions())
                ->setTemplateVars($vars)
                ->setFromByScope($sender);
            foreach ($this->data->getEmails($this->storeManager->getStore()->getId()) as $email) {
                $transportBuilder = $transportBuilder->addTo($email);
            }
            $transport = $transportBuilder->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
        }
    }


    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getTemplateOptions(): array
    {
        $storeId = $this->storeManager->getStore()->getId();
        return array(
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId,
        );
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function validateCaptcha()
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $client = ObjectManager::getInstance()->create(Client::class);
        $remoteAddress = ObjectManager::getInstance()->create(\Magento\Framework\HTTP\PhpEnvironment\RemoteAddress::class);
        $data = array(
            'secret' => '6LdtETgeAAAAAGoqG4_usR0dWFwzzystL36vWLmV',
            'response' => $this->request->getParam('g-recaptcha-response'),
            'remoteip' => $remoteAddress->getRemoteAddress()
        );
        try {
            $response = $client->post($url, ['form_params' => $data]);
        } catch (GuzzleException $e) {
            return false;
        }
        $result = json_decode($response->getBody()->getContents());
        return $result->success;
    }
}
