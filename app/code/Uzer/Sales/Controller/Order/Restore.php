<?php

namespace Uzer\Sales\Controller\Order;

use Magento\Customer\Model\Address\Config;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Core\Mail\Template\TransportBuilder;
use Uzer\Customer\Model\CustomerCustomInfo;
use Uzer\Sales\Helper\Data;
use Uzer\Sales\Model\OrderRestore;
use Uzer\Sales\Model\OrderRestoreFactory;
use Uzer\Sales\Model\ResourceModel\OrderRestoreFactory as ResourceModelFactory;

class Restore implements HttpPostActionInterface
{

    protected CustomerSession $customerSession;
    protected JsonFactory $jsonFactory;
    protected TransportBuilder $transportBuilder;
    protected StateInterface $inlineTranslation;
    protected ScopeConfigInterface $scopeConfig;
    protected StoreManagerInterface $storeManager;
    protected OrderRestoreFactory $orderRestoreFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected Data $helperData;
    protected CustomerCustomInfo $customerCustomInfo;
    protected Http $request;
    protected OrderRepositoryInterface $orderRepository;
    protected Config $addressConfig;
    protected Filesystem $fileSystem;
    protected UploaderFactory $fileUploader;

    /**
     * @param CustomerSession $customerSession
     * @param JsonFactory $jsonFactory
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param OrderRestoreFactory $orderRestoreFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param Data $helperData
     * @param CustomerCustomInfo $customerCustomInfo
     * @param Http $request
     * @param OrderRepositoryInterface $orderRepository
     * @param Config $addressConfig
     * @param Filesystem $fileSystem
     * @param UploaderFactory $fileUploader
     */
    public function __construct(
        CustomerSession          $customerSession,
        JsonFactory              $jsonFactory,
        TransportBuilder         $transportBuilder,
        StateInterface           $inlineTranslation,
        ScopeConfigInterface     $scopeConfig,
        StoreManagerInterface    $storeManager,
        OrderRestoreFactory      $orderRestoreFactory,
        ResourceModelFactory     $resourceModelFactory,
        Data                     $helperData,
        CustomerCustomInfo       $customerCustomInfo,
        Http                     $request,
        OrderRepositoryInterface $orderRepository,
        Config                   $addressConfig,
        Filesystem               $fileSystem,
        UploaderFactory          $fileUploader
    )
    {
        $this->customerSession = $customerSession;
        $this->jsonFactory = $jsonFactory;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->orderRestoreFactory = $orderRestoreFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->customerCustomInfo = $customerCustomInfo;
        $this->request = $request;
        $this->orderRepository = $orderRepository;
        $this->scopeConfig = $scopeConfig;
        $this->addressConfig = $addressConfig;
        $this->fileSystem = $fileSystem;
        $this->fileUploader = $fileUploader;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $json = $this->jsonFactory->create();
        if (!$this->customerSession->isLoggedIn()) {
            $json->setData(['result' => 'Unauthorized', 'code' => 401]);
            return $json;
        }
        try {
            $orderRestore = $this->save();
            $result = $this->uploadFile($orderRestore);
            $this->sendEmail($orderRestore, $result);
            $json->setData(['result' => 'success', 'code' => 200]);
        } catch (\Exception $ex) {
            $json->setData(['result' => 'Error', 'message' => $ex->getMessage(), 'code' => 500]);
        }
        return $json;
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
     * @return OrderRestore
     * @throws AlreadyExistsException
     * @throws \Exception
     */
    public function save(): OrderRestore
    {
        $orderRestore = $this->orderRestoreFactory->create($this->request->getParams());
        $orderRestore->setData($this->request->getParams());
        $this->resourceModelFactory->create()->save($orderRestore);
        return $orderRestore;
    }

    /**
     * @return void
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendEmail(OrderRestore $orderRestore, array $attachment = [])
    {
        $this->inlineTranslation->suspend();
        $email = $this->scopeConfig->getValue('trans_email/ident_support/email', ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getId());
        $sender = [
            'name' => 'Decowraps',
            'email' => $email,
        ];
        $emails = $this->helperData->getEmails($this->storeManager->getStore()->getId());
        $emails[] = $orderRestore->getEmail();
        $templateId = $this->helperData->getTemplate($this->storeManager->getStore()->getId());
        foreach ($emails as $email) {
            $transportBuilder = $this->transportBuilder->setTemplateIdentifier($templateId);
            $transportBuilder->resetAttachments();
            if ($attachment) {
                $transportBuilder->addAttachment($attachment['content'], $attachment['name'], $attachment['type']);
            }
            $transportBuilder->setTemplateOptions($this->getTemplateOptions())
                ->setTemplateVars($this->getTemplateVars($orderRestore))
                ->setFromByScope($sender);
            $transportBuilder->addTo($email);
            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();
        }
        $this->inlineTranslation->resume();
    }

    public function getTemplateVars(OrderRestore $orderRestore): array
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->orderRepository->get($orderRestore->getOrderId());
        $data = $orderRestore->toArray();
        $data['company'] = $this->customerCustomInfo->getByCustomerId($this->customerSession->getId(), 'company_data');
        $data['customer'] = $this->customerSession->getCustomer();
        $data['order'] = $order;
        $data['formattedDate'] = $order->getCreatedAtFormatted(2);
        try {
            /** @var \ Magento\Customer\Block\Address\Renderer\DefaultRenderer $renderer */
            $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();
            $data['formattedShippingAddress'] = $renderer->renderArray($order->getShippingAddress()->toArray());
        } catch (\Exception $ex) {
        }

        return $data;
    }

    /**
     * @throws FileSystemException
     * @throws \Exception
     */
    public function uploadFile(OrderRestore $orderRestore): ?array
    {
        if (isset($_FILES['picture']) && $_FILES['picture']['tmp_name']) {
            $destinationPath = $this->getDestinationPath();
            $filename = $_FILES['picture']['name']; // Obtener el nombre original del archivo
            $ext = pathinfo($filename, PATHINFO_EXTENSION); // Obtener la extensión del archivo
            $newFilename = 'restore' . '-' . $orderRestore->getId() . '.' . $ext; // Nuevo nombre con extensión
            $uploader = $this->fileUploader->create(['fileId' => 'picture'])->setAllowCreateFolders(true)->setAllowedExtensions(['png', 'jpg', 'jpeg']);
            $result = $uploader->save($destinationPath, $newFilename); // Usar el nuevo nombre de archivo
            $path = sprintf('%s%s', $result['path'], $result['file']);
            $fileContent = file_get_contents($path);
            return ['content' => $fileContent, 'name' => $filename, 'type' => $_FILES['picture']['type']];
        }
        return [];
    }

    /**
     * @return string
     * @throws FileSystemException
     */
    public function getDestinationPath(): string
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::PUB)
            ->getAbsolutePath('/media/returns/documents/');
    }
}
