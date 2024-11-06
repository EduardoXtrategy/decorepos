<?php

namespace Uzer\Customer\Controller\Register;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModelCustomer;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModelCustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Filesystem;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Validation\ValidationException;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Uzer\Core\Model\SaveAddress;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\InformationBusinessFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModel;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Framework\Event\ManagerInterface;

class Submit implements HttpPostActionInterface
{

    protected Filesystem $fileSystem;
    protected UploaderFactory $fileUploader;
    protected RequestInterface $request;
    protected ResourceModel $resourceModel;
    protected InformationBusinessFactory $informationBusinessFactory;
    protected ResultFactory $resultFactory;
    protected Session $session;
    protected SaveAddress $saveAddress;
    protected MessageManagerInterface $messageManager;
    protected AddressInterfaceFactory $addressDataFactory;
    protected CustomerFactory $customerFactory;
    protected ManagerInterface $eventManager;
    protected ResourceModelCustomer $resourceModelCustomer;
    protected Customer $customer;


    /**
     * @param Filesystem $fileSystem
     * @param UploaderFactory $fileUploader
     * @param RequestInterface $request
     * @param ResourceModel $resourceModel
     * @param InformationBusinessFactory $informationBusinessFactory
     * @param ResultFactory $resultFactory
     * @param Session $session
     * @param SaveAddress $saveAddress
     * @param MessageManagerInterface $messageManager
     * @param AddressInterfaceFactory $addressDataFactory
     * @param CustomerFactory $customerFactory
     * @param ManagerInterface $eventManager
     * @param ResourceModelCustomerFactory $resourceModelCustomer
     */
    public function __construct(
        Filesystem                   $fileSystem,
        UploaderFactory              $fileUploader,
        RequestInterface             $request,
        ResourceModel                $resourceModel,
        InformationBusinessFactory   $informationBusinessFactory,
        ResultFactory                $resultFactory,
        Session                      $session,
        SaveAddress                  $saveAddress,
        MessageManagerInterface      $messageManager,
        AddressInterfaceFactory      $addressDataFactory,
        CustomerFactory              $customerFactory,
        ManagerInterface             $eventManager,
        ResourceModelCustomerFactory $resourceModelCustomer
    )
    {
        $this->fileSystem = $fileSystem;
        $this->fileUploader = $fileUploader;
        $this->request = $request;
        $this->resourceModel = $resourceModel;
        $this->informationBusinessFactory = $informationBusinessFactory;
        $this->resultFactory = $resultFactory;
        $this->session = $session;
        $this->saveAddress = $saveAddress;
        $this->messageManager = $messageManager;
        $this->addressDataFactory = $addressDataFactory;
        $this->customerFactory = $customerFactory;
        $this->resourceModelCustomer = $resourceModelCustomer->create();
        $this->eventManager = $eventManager;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     * @throws AlreadyExistsException
     * @throws LocalizedException
     * @throws \Exception
     */
    public function execute()
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->session->isLoggedIn()) {
            $redirect->setPath('customer/account/login');
            return $redirect;
        }
        $informationBusiness = $this->informationBusinessFactory->create($this->request->getParams());
        $informationBusiness->setData($this->request->getParams());
        $this->customer = $this->customerFactory->create();
        $this->resourceModelCustomer->load($this->customer, $this->session->getCustomerId());
        try {
            $this->uploadFiles($informationBusiness);
        } catch (ValidationException $ex) {
            $this->messageManager->addErrorMessage(__('Only pdf files are allowed'));
            $redirect->setPath('customers/register/step');
            return $redirect;
        }
        $address = $this->addressDataFactory->create();
        $phone = $this->getPhone();
        $address->setTelephone($phone);
        $this->saveCustomer($this->customer);
        $address = $this->saveAddress->execute($address);
        $this->customer->setDefaultBilling($address->getId());
        $this->resourceModelCustomer->save($this->customer);
        $this->saveInformationBusiness($address, $informationBusiness);
        $this->eventManager->dispatch('bussines_information_success', [
            'informationBusiness' => $informationBusiness,
            'customer' => $this->customer,
            'address' => $address
        ]);
        $redirect->setPath('customers/register/success');
        return $redirect;
    }

    /**
     * @throws LocalizedException
     */
    public function getPhone()
    {
        $resourceConnection = ObjectManager::getInstance()->get(ResourceConnection::class);
        $table = $resourceConnection->getTableName('customer_entity_varchar');
        $connection = $resourceConnection->getConnection();
        $attributeId = $this->resourceModelCustomer->getAttribute('phone')->getId();
        $result = $connection->fetchAll('SELECT * FROM ' . $table . ' WHERE attribute_id = ' . $attributeId . ' AND entity_id = ' . $this->customer->getId(), ['value']);
        if (count($result) > 0) {
            return $result[0]['value'];
        }
        return null;
    }

    /**
     * @throws LocalizedException
     * @throws \Exception
     */
    public function uploadFiles(InformationBusiness $informationBusiness)
    {
        $destinationPath = $this->getDestinationPath();
        if (isset($_FILES['new_customer_form']) && $_FILES['new_customer_form']['tmp_name']) {
            $value = $this->uploadFile('new_customer_form', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setNewCustomerFormDocument(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['credit_application']) && $_FILES['credit_application']['tmp_name']) {
            $value = $this->uploadFile('credit_application', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setRequestPaymentTerms(true);
            $informationBusiness->setCreditApplicationDocument(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['tax_certificate']) && $_FILES['tax_certificate']['tmp_name']) {
            $value = $this->uploadFile('tax_certificate', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setRequestTaxException(true);
            $informationBusiness->setTaxExceptionDocument(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['wp9']) && $_FILES['wp9']['tmp_name']) {
            $value = $this->uploadFile('wp9', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setWp9Document(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['responsibility_agreement_doc']) && $_FILES['responsibility_agreement_doc']['tmp_name']) {
            $value = $this->uploadFile('responsibility_agreement_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setResponsibilityAgreementDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['circular_format_170_doc']) && $_FILES['circular_format_170_doc']['tmp_name']) {
            $value = $this->uploadFile('circular_format_170_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setCircularFormat170Doc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['personal_data_processing_doc']) && $_FILES['personal_data_processing_doc']['tmp_name']) {
            $value = $this->uploadFile('personal_data_processing_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setPersonalDataProcessingDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['chamber_commerce_certificate_doc']) && $_FILES['chamber_commerce_certificate_doc']['tmp_name']) {
            $value = $this->uploadFile('chamber_commerce_certificate_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setChamberCommerceCertificateDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['rut_doc']) && $_FILES['rut_doc']['tmp_name']) {
            $value = $this->uploadFile('rut_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setRutDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['copy_legal_representative_doc']) && $_FILES['copy_legal_representative_doc']['tmp_name']) {
            $value = $this->uploadFile('copy_legal_representative_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setCopyLegalRepresentativeDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['commercial_reference_doc']) && $_FILES['commercial_reference_doc']['tmp_name']) {
            $value = $this->uploadFile('commercial_reference_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setCommercialReferenceDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['bank_reference_doc']) && $_FILES['bank_reference_doc']['tmp_name']) {
            $value = $this->uploadFile('bank_reference_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setBankReferenceDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['financial_statements_doc']) && $_FILES['financial_statements_doc']['tmp_name']) {
            $value = $this->uploadFile('financial_statements_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setFinancialStatementsDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['basc_certification']) && $_FILES['basc_certification']['tmp_name']) {
            $value = $this->uploadFile('basc_certification', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setBascCertification(sprintf('%s%s', $value['path'], $value['file']));
        }
        if (isset($_FILES['shareholding_comp_cert_doc']) && $_FILES['shareholding_comp_cert_doc']['tmp_name']) {
            $value = $this->uploadFile('shareholding_comp_cert_doc', $destinationPath);
            if (!$value) {
                throw new LocalizedException(
                    __('File cannot be saved')
                );
            }
            $informationBusiness->setShareholdingCompCertDoc(sprintf('%s%s', $value['path'], $value['file']));
        }
    }


    /**
     * @throws LocalizedException
     * @throws \Exception
     */
    public function uploadFile(string $id, string $destinationPath)
    {
        $uploader = $this->fileUploader->create(['fileId' => $id])->setAllowCreateFolders(true)->setAllowedExtensions(['pdf']);
        return $uploader->save($destinationPath, $id . '_' . $this->session->getCustomerId() . '.pdf');
    }

    /**
     * @throws FileSystemException
     */
    public function getDestinationPath(): string
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::VAR_DIR)
            ->getAbsolutePath('/customer/documents/');
    }

    /**
     * @throws AlreadyExistsException
     */
    public function saveInformationBusiness(AddressInterface $address, InformationBusiness $informationBusiness): InformationBusiness
    {
        $informationBusiness->setAddressesId($address->getId());
        $informationBusiness->setCustomersId($this->session->getCustomerId());
        $this->resourceModel->save($informationBusiness);
        return $informationBusiness;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function saveCustomer(Customer $customer)
    {
        $vat = $this->request->getParam('tax_vat');
        if ($vat) {
            $customer->setData('taxvat', $vat);
            $customer->getDataModel()->setTaxvat($vat);
            $this->resourceModelCustomer->save($customer);
        }
    }
}
