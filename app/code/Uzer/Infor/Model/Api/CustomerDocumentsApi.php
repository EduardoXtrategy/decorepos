<?php

namespace Uzer\Infor\Model\Api;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\LocalizedException;
use Uzer\Customer\Api\Data\InformationBusinessInterface;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\InformationBusinessFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModelInformation;
use Uzer\Infor\Helper\Data;
use Uzer\Infor\Logger\Logger;
use Uzer\Infor\Model\Builder\BuilderDocument;

class CustomerDocumentsApi
{

    protected ResourceModelInformation $informationBusinessResource;
    protected InformationBusinessFactory $informationBusinessFactory;
    protected BuilderDocument $builderDocument;
    protected ClientFactory $clientFactory;
    protected Data $data;
    protected Logger $logger;
    protected ?InformationBusiness $informationBusinessModel;

    private array $documents = [
        'taxExceptionDoc' => InformationBusinessInterface::TAX_EXCEPTION_DOCUMENT,
        'w9Doc' => InformationBusinessInterface::WP9_DOCUMENT,
        'creditApplicationDoc' => InformationBusinessInterface::CREDIT_APPLICATION_DOCUMENT,
        'vATDoc' => InformationBusinessInterface::RUT_DOC,
        'responsibilityAgreementDoc' => InformationBusinessInterface::RESPONSIBILITY_AGREEMENT_DOC,
        'cirularFormat170Doc' => InformationBusinessInterface::CIRCULAR_FORMAT_170_DOC,
        'personalDataProcessingDoc' => InformationBusinessInterface::PERSONAL_DATA_PROCESSING_DOC,
        'chamberCommerceCertificateDoc' => InformationBusinessInterface::CHAMBER_COMMERCE_CERTIFICATE_DOC,
        'rutDoc' => InformationBusinessInterface::RUT_DOC,
        'copyLegalRepresentativeDoc' => InformationBusinessInterface::COPY_LEGAL_REPRESENTATIVE_DOC,
        'commercialReferenceDoc' => InformationBusinessInterface::COMMERCIAL_REFERENCE_DOC,
        'bankReferenceDoc' => InformationBusinessInterface::BANK_REFERENCE_DOC,
        'financialStatementsDoc' => InformationBusinessInterface::FINANCIAL_STATEMENTS_DOC,
        'bascCertificationDoc' => InformationBusinessInterface::BASC_CERTIFICATION,
        'shareholdingCompCertDoc' => InformationBusinessInterface::SHAREHOLDING_COMP_CERT_DOC,
    ];


    public function __construct(
        ResourceModelInformation   $informationBusinessResource,
        InformationBusinessFactory $informationBusinessFactory,
        BuilderDocument            $builderDocument,
        ClientFactory              $clientFactory,
        Data                       $data,
        Logger                     $logger
    )
    {
        $this->informationBusinessResource = $informationBusinessResource;
        $this->informationBusinessFactory = $informationBusinessFactory;
        $this->builderDocument = $builderDocument;
        $this->logger = $logger;
        $this->clientFactory = $clientFactory;
        $this->data = $data;
    }

    /**
     * @param string $token
     * @param Customer $customer
     * @return void
     * @throws LocalizedException
     * @throws GuzzleException
     */
    public function dispatch(string $token, Customer $customer)
    {
        $this->init($customer);
        $client = $this->clientFactory->create();
        $apiUrl = sprintf(
            '%s%s/IDM/api/items',
            $this->data->getApiUrl($customer->getStoreId()),
            $this->data->getTenantId($customer->getStoreId())
        );
        $this->logger->info('API URL: ' . $apiUrl);
        foreach ($this->documents as $key => $document) {
            $path = $this->informationBusinessModel->getData($document);
            if (!$path) {
                continue;
            }
            $filename = sprintf('%s-%s.pdf', $key, $customer->getId());
            $requestData = $this->builderDocument->build($customer, $path, $key, $filename);
            $this->logger->info('Send Request: ' . json_encode($requestData));
            try {
                $response = $client->request('POST', $apiUrl,
                    [
                        'headers' =>
                            [
                                'Authorization' => 'Bearer ' . $token,
                                'X-Infor-MongooseConfig' => $this->data->getMoongooseConfig($customer->getStoreId()),
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                            ],
                        'json' => $requestData,
                    ]);
                $this->logger->info('Response: ' . $response->getBody()->getContents());
            } catch (GuzzleException $e) {
                $this->logger->info('Error: ' . $e->getMessage());
                throw $e;
            }
        }
    }

    /**
     * @throws LocalizedException
     */
    private function init(Customer $customer)
    {
        $this->informationBusinessModel = $this->informationBusinessFactory->create();
        $this->informationBusinessResource->loadByCustomerId($this->informationBusinessModel, $customer->getId());
    }

}
