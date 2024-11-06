<?php

namespace Uzer\Samples\Controller\Checkout;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Uzer\Samples\Helper\Data;
use Uzer\Samples\Model\ResourceModel\SampleOrderFactory as ResourceModel;
use Uzer\Samples\Model\SampleOrder;
use Uzer\Samples\Model\SampleOrderFactory;
use Uzer\Samples\Model\Session;
use \Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Samples\Model\ResourceModel\SamplesCartFactory;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Directory\Model\CountryFactory;

class Complete implements HttpPostActionInterface
{

    private Session $session;
    private CustomerSession $customerSession;
    private ResourceModel $resourceModel;
    private SampleOrderFactory $sampleOrderFactory;
    private RedirectFactory $redirectFactory;
    protected RequestInterface $request;
    protected TransportBuilder $transportBuilder;
    protected StoreManagerInterface $storeManager;
    protected StateInterface $inlineTranslation;
    protected SamplesCartFactory $samplesCartFactory;
    protected AddressRepositoryInterface $addressRepositoryInterface;
    protected CountryFactory $countryFactory;
    protected Data $data;


    /**
     * @param Session $session
     * @param ResourceModel $resourceModel
     * @param SampleOrderFactory $sampleOrderFactory
     * @param CustomerSession $customerSession
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param Context $context
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $state
     * @param SamplesCartFactory $samplesCartFactory
     * @param AddressRepositoryInterface $addressRepositoryInterface
     * @param CountryFactory $countryFactory
     * @param Data $data
     */
    public function __construct(
        Session                    $session,
        ResourceModel              $resourceModel,
        SampleOrderFactory         $sampleOrderFactory,
        CustomerSession            $customerSession,
        RequestInterface           $request,
        RedirectFactory            $redirectFactory,
        Context                    $context,
        TransportBuilder           $transportBuilder,
        StoreManagerInterface      $storeManager,
        StateInterface             $state,
        SamplesCartFactory         $samplesCartFactory,
        AddressRepositoryInterface $addressRepositoryInterface,
        CountryFactory             $countryFactory,
        Data                       $data
    )
    {
        $this->session = $session;
        $this->resourceModel = $resourceModel;
        $this->sampleOrderFactory = $sampleOrderFactory;
        $this->customerSession = $customerSession;
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        $this->samplesCartFactory = $samplesCartFactory;
        $this->addressRepositoryInterface = $addressRepositoryInterface;
        $this->countryFactory = $countryFactory;
        $this->data = $data;
    }


    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\SessionException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $sampleOrder = $this->sampleOrderFactory->create();
        $samplesCart = $this->session->getSamplesCart();
        $samplesCart->setActive(false);
        $this->samplesCartFactory->create()->save($samplesCart);
        $resourceModel = $this->resourceModel->create();
        $sampleOrder->setCustomersId($this->customerSession->getCustomerId());
        $sampleOrder->setDatePurchase(date('Y-m-d H:i:s'));
        $sampleOrder->setSampleQuoteId($samplesCart->getId());
        $sampleOrder->setNote($this->request->getParam('note-delivery'));
        $sampleOrder->setCustomerAddressId($this->request->getParam('address_code'));
        $sampleOrder->setFullName($this->customerSession->getCustomer()->getName());
        $sampleOrder->setFirstName($this->customerSession->getCustomer()->getFirstname());
        $sampleOrder->setLastName($this->customerSession->getCustomer()->getLastname());
        $sampleOrder->setEmail($this->customerSession->getCustomer()->getEmail());
        $sampleOrder->setStoreId($this->storeManager->getStore()->getId());
        $resourceModel->save($sampleOrder);
        $this->sendEmail($sampleOrder);
        return $this->redirectFactory->create()->setPath('samples/checkout/success', array('order' => $sampleOrder->getId()));
    }

    /**
     * @param SampleOrder $sampleOrder
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendEmail(SampleOrder $sampleOrder)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
        $email = $scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sender = [
            'name' => 'Decowraps',
            'email' => $email,
        ];
        try {
            $templateId = $this->data->emailTemplateId($this->storeManager->getStore()->getId());
            $this->inlineTranslation->suspend();
            $transportBuilder = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($this->getTemplateOptions())
                ->setTemplateVars($this->getTemplateVars($sampleOrder))
                ->setFromByScope($sender)
                ->addTo($this->customerSession->getCustomer()->getEmail());
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

    public function getTemplateVars(SampleOrder $sampleOrder): array
    {
        $addressRepository = $this->addressRepositoryInterface->getById($sampleOrder->getCustomerAddressId());
        $street_array = $addressRepository->getStreet();
        $street_1 = $street_array[0] ?? '';
        $street_2 = ($street_array[1] ?? '') . ' ' . ($street_array[2] ?? '');
        $country = $this->countryFactory->create()->loadByCode($addressRepository->getCountryId());
        $data = [
            'name' => $this->customerSession->getCustomer()->getName(),
            'orderNumber' => $sampleOrder->getId(),
            'date' => date("M j, Y"),
            'street_primary' => $street_1,
            'street_second' => $street_2,
            'country' => $country->getName(),
            'region' => $addressRepository->getRegion()->getRegion(),
            'city' => $addressRepository->getCity(),
            'postcode' => $addressRepository->getPostCode(),
            'telephone' => $addressRepository->getTelephone(),
            'samples_order_id' => $sampleOrder->getId(),
            'note' => $sampleOrder->getNote(),
            'company_address' => $addressRepository->getCompany()
        ];
        $company = $this->getCompany();

        if ($company) {
            $data['company'] = $company;
        }
        return $data;
    }


    public function getCompany()
    {
        $resourceConnection = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\App\ResourceConnection::class);
        $table = $resourceConnection->getTableName('customer_entity_varchar');
        $connection = $resourceConnection->getConnection();
        $customerId = $this->customerSession->getCustomerId();
        $attributeId = $this->customerSession->getCustomer()->getResource()->getAttribute('company_data')->getId();
        $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $customerId, ['value']);
        if (count($result) > 0) {
            return $result[0]['value'];
        }
        return null;
    }

}
