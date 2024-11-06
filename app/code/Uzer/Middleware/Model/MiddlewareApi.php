<?php

namespace Uzer\Middleware\Model;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\CustomerFactory as ResourceModel;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Webapi\Rest\Request;
use Uzer\Customer\Model\CustomerCustomInfo;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Middleware\Logger\Logger;

class MiddlewareApi
{

    protected CountryInformationAcquirerInterface $country;
    protected ResourceModel $resourceModel;
    protected CountryFactory $countryFactory;
    protected ApiRequest $apiRequest;
    protected Logger $logger;
    protected ResourceConnection $resourceConnection;
    protected CustomerCustomInfo $customerCustomInfo;

    /**
     * @param CountryInformationAcquirerInterface $country
     * @param ResourceModel $resourceModel
     * @param CountryFactory $countryFactory
     * @param ApiRequest $apiRequest
     * @param Logger $logger
     * @param ResourceConnection $resourceConnection
     * @param CustomerCustomInfo $customerCustomInfo
     */
    public function __construct(
        CountryInformationAcquirerInterface $country,
        ResourceModel                       $resourceModel,
        CountryFactory                      $countryFactory,
        ApiRequest                          $apiRequest,
        Logger                              $logger,
        ResourceConnection                  $resourceConnection,
        CustomerCustomInfo                  $customerCustomInfo
    )
    {
        $this->apiRequest = $apiRequest;
        $this->logger = $logger;
        $this->countryFactory = $countryFactory;
        $this->country = $country;
        $this->resourceModel = $resourceModel;
        $this->resourceConnection = $resourceConnection;
        $this->customerCustomInfo = $customerCustomInfo;
    }

    public function send(string $token, Customer $customer, AddressInterface $address, InformationBusiness $informationBusiness)
    {
        $countryCode = trim($address->getCountryId());
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        $addresses = [
            [
                'magentoAddressId' => (int)$address->getId(),
                'customerId' => (int)$customer->getId(),
                'type' => 'B',
                'principal' => true,
                'address' => join(' , ', $address->getStreet()),
                'city' => $address->getCity(),
                'state' => $address->getRegion()->getRegion(),
                'zip' => $address->getPostcode(),
                'country' => $country->getName(),
                'phone' => $address->getTelephone(),
                'fax' => $address->getFax()
            ]
        ];
        $customerInfo = $customer->getDataModel();
        $companyName = $this->getCompany($customer);
        $title = $this->getTitle($customer);
        $areaCode = $this->customerCustomInfo->get($customer, 'area_code');
        $wp9Document = null;
        $base64TaxExceptionDocument = null;
        $creditApplicationDocument = null;
        $customerFormDocument = null;
        $responsibilityAgreementDocument = null;
        $circularFormat170Document = null;
        $personalDataProcessingDocument = null;
        $chamberCommerceCertificateDocument = null;
        $rutDocument = null;
        $copyLegalRepresentativeDocument = null;
        $commercialReferenceDocument = null;
        $bankReferenceDocument = null;
        $financialStatementsDocument = null;
        $bascCertificationument = null;
        $shareholdingCompCertDocument = null;
        $wp9 = $informationBusiness->getWp9Document();
        $taxExceptionDocument = $informationBusiness->getTaxExceptionDocument();
        $creditApplication = $informationBusiness->getCreditApplicationDocument();
        $customerForm = $informationBusiness->getNewCustomerFormDocument();
        $responsibilityAgreementDoc = $informationBusiness->getResponsibilityAgreementDoc();
        $circularFormat170Doc = $informationBusiness->getCircularFormat170Doc();
        $personalDataProcessingDoc = $informationBusiness->getPersonalDataProcessingDoc();
        $chamberCommerceCertificateDoc = $informationBusiness->getChamberCommerceCertificateDoc();
        $rutDoc = $informationBusiness->getRutDoc();
        $copyLegalRepresentativeDoc = $informationBusiness->getCopyLegalRepresentativeDoc();
        $commercialReferenceDoc = $informationBusiness->getCommercialReferenceDoc();
        $bankReferenceDoc = $informationBusiness->getBankReferenceDoc();
        $financialStatementsDoc = $informationBusiness->getFinancialStatementsDoc();
        $bascCertification = $informationBusiness->getBascCertification();
        $shareholdingCompCertDoc = $informationBusiness->getShareholdingCompCertDoc();
        if ($wp9) {
            $content = file_get_contents($informationBusiness->getWp9Document());
            $wp9Document = base64_encode($content);
        }
        if ($taxExceptionDocument) {
            $content = file_get_contents($informationBusiness->getTaxExceptionDocument());
            $base64TaxExceptionDocument = base64_encode($content);
        }
        if ($creditApplication) {
            $content = file_get_contents($informationBusiness->getCreditApplicationDocument());
            $creditApplicationDocument = base64_encode($content);
        }
        if ($customerForm) {
            $content = file_get_contents($informationBusiness->getNewCustomerFormDocument());
            $customerFormDocument = base64_encode($content);
        }
        if ($responsibilityAgreementDoc) {
            $content = file_get_contents($informationBusiness->getResponsibilityAgreementDoc());
            $responsibilityAgreementDocument = base64_encode($content);
        }
        if ($circularFormat170Doc) {
            $content = file_get_contents($informationBusiness->getCircularFormat170Doc());
            $circularFormat170Document = base64_encode($content);
        }
        if ($personalDataProcessingDoc) {
            $content = file_get_contents($informationBusiness->getPersonalDataProcessingDoc());
            $personalDataProcessingDocument = base64_encode($content);
        }
        if ($chamberCommerceCertificateDoc) {
            $content = file_get_contents($informationBusiness->getChamberCommerceCertificateDoc());
            $chamberCommerceCertificateDocument = base64_encode($content);
        }
        if ($rutDoc) {
            $content = file_get_contents($informationBusiness->getRutDoc());
            $rutDocument = base64_encode($content);
        }
        if ($copyLegalRepresentativeDoc) {
            $content = file_get_contents($informationBusiness->getCopyLegalRepresentativeDoc());
            $copyLegalRepresentativeDocument = base64_encode($content);
        }
        if ($commercialReferenceDoc) {
            $content = file_get_contents($informationBusiness->getCommercialReferenceDoc());
            $commercialReferenceDocument = base64_encode($content);
        }
        if ($bankReferenceDoc) {
            $content = file_get_contents($informationBusiness->getBankReferenceDoc());
            $bankReferenceDocument = base64_encode($content);
        }
        if ($financialStatementsDoc) {
            $content = file_get_contents($informationBusiness->getFinancialStatementsDoc());
            $financialStatementsDocument = base64_encode($content);
        }
        if ($bascCertification) {
            $content = file_get_contents($informationBusiness->getBascCertification());
            $bascCertificationument = base64_encode($content);
        }
        if ($shareholdingCompCertDoc) {
            $content = file_get_contents($informationBusiness->getShareholdingCompCertDoc());
            $shareholdingCompCertDocument = base64_encode($content);
        }

        $customerData = [
            "magentoId" => (int)$customerInfo->getId(),
            "websiteId" => (int)$customer->getStore()->getWebsiteId(),
            "companyName" => $companyName,
            "firstName" => $customerInfo->getFirstName(),
            "lastName" => $customerInfo->getLastname(),
            "email" => $customerInfo->getEmail(),
            "phone" => $address->getTelephone(),
            "taxException" => $informationBusiness->getRequestTaxException(),
            "paymentTerms" => $informationBusiness->getRequestPaymentTerms(),
            "taxExceptionDoc" => $base64TaxExceptionDocument,
            "w9Doc" => $wp9Document,
            'creditApplicationDoc' => $creditApplicationDocument,
            "vATDoc" => $customerFormDocument,
            "address" => $addresses,
            'title' => $title,
            'codeCountry' => $areaCode,
            'responsibilityAgreementDoc' => $responsibilityAgreementDocument,
            'cirularFormat170Doc' => $circularFormat170Document,
            'personalDataProcessingDoc' => $personalDataProcessingDocument,
            'chamberCommerceCertificateDoc' => $chamberCommerceCertificateDocument,
            'rutDoc' => $rutDocument,
            'copyLegalRepresentativeDoc' => $copyLegalRepresentativeDocument,
            'commercialReferenceDoc' => $commercialReferenceDocument,
            'bankReferenceDoc' => $bankReferenceDocument,
            'financialStatementsDoc' => $financialStatementsDocument,
            'bascCertificationDoc' => $bascCertificationument,
            'shareholdingCompCertDoc' => $shareholdingCompCertDocument
        ];
        if ($informationBusiness->getTaxExceptionTo()) {
            try {
                $date = \DateTime::createFromFormat('m/d/Y', $informationBusiness->getTaxExceptionTo());
                if ($date instanceof \DateTime) {
                    $formattedDate = $date->format('Y-m-d');
                    $customerData['taxExcemptionDate'] = $formattedDate;
                }
            } catch (\Exception $ex) {

            }
        }

        $this->processVat($customer, $customerInfo, $customerData);
        $requestData = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Bearer %s', $token),
                'x-username' => 'EMagento',
                'x-origin' => 'Magento'
            ],
            'json' => $customerData,
        ];
        //get home directory magento
        file_put_contents(sprintf(BP . '/var/log/customer-%s.json', $customer->getId()), json_encode($requestData));
        $response = $this->apiRequest->doRequest('customer', $requestData, Request::HTTP_METHOD_POST);
        if ($response->getStatusCode() == 200) {
            $this->logger->info('Customer created: ' . json_encode($customerData) . ' Response: ' . $response->getBody()->getContents());
            return json_encode($response->getBody()->getContents());
        } else {
            $this->logger->info('Customer Request: ' . json_encode($customerData) . ' Response: ' . $response->getBody()->getContents() . ' Code: ' . $response->getStatusCode());
            throw new \RuntimeException($response->getStatusCode());
        }
    }

    public function getCompany(Customer $customer)
    {
        return $this->customerCustomInfo->get($customer, 'company_data');
    }

    public function getTitle(Customer $customer)
    {
        return $this->customerCustomInfo->get($customer, 'title_data');
    }

    public function processVat(Customer $customer, CustomerInterface $customerInfo, array &$data = [])
    {
        $sendIdentification = ['lat_es', 'lat_en'];
        if (in_array($customer->getStore()->getCode(), $sendIdentification)) {
            $data['identification'] = $this->getIdentification($customerInfo);
        } else {
            $data['vAT'] = $customerInfo->getTaxvat();
        }
    }

    private function getIdentification(CustomerInterface $customerInfo): ?int
    {
        $vat = $items = explode("-", $customerInfo->getTaxvat());
        if ($vat && count($vat) > 0) {
            return (int)$vat[0];
        }
        return null;
    }
}
