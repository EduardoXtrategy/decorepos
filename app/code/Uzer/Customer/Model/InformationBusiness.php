<?php

namespace Uzer\Customer\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Customer\Api\Data\InformationBusinessInterface;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModel;

class InformationBusiness extends AbstractModel implements InformationBusinessInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'customers_information_business_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Getter for AssistanceAllowed.
     *
     * @return bool|null
     */
    public function getAllowPurchases(): ?bool
    {
        return $this->getData(self::ALLOW_PURCHASES) === null ? null
            : (bool)$this->getData(self::ALLOW_PURCHASES);
    }

    /**
     * Setter for AssistanceAllowed.
     *
     * @param bool|null $allowPurchases
     *
     * @return void
     */
    public function setAllowPurchases(?bool $allowPurchases): void
    {
        $this->setData(self::ALLOW_PURCHASES, $allowPurchases);
    }

    public function getRequestAllowPurchases(): ?bool
    {
        return $this->getData(self::REQUEST_ALLOW_PURCHASES) === null ? null
            : (bool)$this->getData(self::REQUEST_ALLOW_PURCHASES);
    }

    public function setRequestAllowPurchases(?bool $allowPurchases): void
    {
        $this->setData(self::REQUEST_ALLOW_PURCHASES, $allowPurchases);
    }


    /**
     * Getter for TaxException.
     *
     * @return bool|null
     */
    public function getTaxException(): ?bool
    {
        return $this->getData(self::TAX_EXCEPTION) === null ? null
            : (bool)$this->getData(self::TAX_EXCEPTION);
    }

    /**
     * Setter for TaxException.
     *
     * @param bool|null $taxException
     *
     * @return void
     */
    public function setTaxException(?bool $taxException): void
    {
        $this->setData(self::TAX_EXCEPTION, $taxException);
    }

    public function getRequestTaxException(): ?bool
    {
        return $this->getData(self::REQUEST_TAX_EXCEPTION) === null ? null
            : (bool)$this->getData(self::REQUEST_TAX_EXCEPTION);
    }

    public function setRequestTaxException(?bool $taxException): void
    {
        $this->setData(self::REQUEST_TAX_EXCEPTION, $taxException);
    }

    /**
     * Getter for TaxExceptionFrom.
     *
     * @return string|null
     */
    public function getTaxExceptionFrom(): ?string
    {
        return $this->getData(self::TAX_EXCEPTION_FROM);
    }

    /**
     * Setter for TaxExceptionFrom.
     *
     * @param string|null $taxExceptionFrom
     *
     * @return void
     */
    public function setTaxExceptionFrom(?string $taxExceptionFrom): void
    {
        $this->setData(self::TAX_EXCEPTION_FROM, $taxExceptionFrom);
    }

    /**
     * Getter for TaxExceptionTo.
     *
     * @return string|null
     */
    public function getTaxExceptionTo(): ?string
    {
        return $this->getData(self::TAX_EXCEPTION_TO);
    }

    /**
     * Setter for TaxExceptionTo.
     *
     * @param string|null $taxExceptionTo
     *
     * @return void
     */
    public function setTaxExceptionTo(?string $taxExceptionTo): void
    {
        $this->setData(self::TAX_EXCEPTION_TO, $taxExceptionTo);
    }

    /**
     * Getter for TaxExceptionDocument.
     *
     * @return string|null
     */
    public function getTaxExceptionDocument(): ?string
    {
        return $this->getData(self::TAX_EXCEPTION_DOCUMENT);
    }

    /**
     * Setter for TaxExceptionDocument.
     *
     * @param string|null $taxExceptionDocument
     *
     * @return void
     */
    public function setTaxExceptionDocument(?string $taxExceptionDocument): void
    {
        $this->setData(self::TAX_EXCEPTION_DOCUMENT, $taxExceptionDocument);
    }

    /**
     * Getter for PaymentTerms.
     *
     * @return bool|null
     */
    public function getPaymentTerms(): ?bool
    {
        return $this->getData(self::PAYMENT_TERMS) === null ? null
            : (bool)$this->getData(self::PAYMENT_TERMS);
    }

    /**
     * Setter for PaymentTerms.
     *
     * @param bool|null $paymentTerms
     *
     * @return void
     */
    public function setPaymentTerms(?bool $paymentTerms): void
    {
        $this->setData(self::PAYMENT_TERMS, $paymentTerms);
    }

    /**
     * Getter for PaymentTerms.
     *
     * @return bool|null
     */
    public function getRequestPaymentTerms(): ?bool
    {
        return $this->getData(self::REQUEST_PAYMENT_TERMS) === null ? null
            : (bool)$this->getData(self::REQUEST_PAYMENT_TERMS);
    }

    /**
     * Setter for PaymentTerms.
     *
     * @param bool|null $paymentTerms
     *
     * @return void
     */
    public function setRequestPaymentTerms(?bool $paymentTerms): void
    {
        $this->setData(self::REQUEST_PAYMENT_TERMS, $paymentTerms);
    }

    /**
     * Getter for PaymentTermsRequestDate.
     *
     * @return string|null
     */
    public function getPaymentTermsRequestDate(): ?string
    {
        return $this->getData(self::PAYMENT_TERMS_REQUEST_DATE);
    }

    /**
     * Setter for PaymentTermsRequestDate.
     *
     * @param string|null $paymentTermsRequestDate
     *
     * @return void
     */
    public function setPaymentTermsRequestDate(?string $paymentTermsRequestDate): void
    {
        $this->setData(self::PAYMENT_TERMS_REQUEST_DATE, $paymentTermsRequestDate);
    }

    /**
     * Getter for PaymentTermsResponseDate.
     *
     * @return string|null
     */
    public function getPaymentTermsResponseDate(): ?string
    {
        return $this->getData(self::PAYMENT_TERMS_RESPONSE_DATE);
    }

    /**
     * Setter for PaymentTermsResponseDate.
     *
     * @param string|null $paymentTermsResponseDate
     *
     * @return void
     */
    public function setPaymentTermsResponseDate(?string $paymentTermsResponseDate): void
    {
        $this->setData(self::PAYMENT_TERMS_RESPONSE_DATE, $paymentTermsResponseDate);
    }

    /**
     * Getter for PaymentTermsDocument.
     *
     * @return string|null
     */
    public function getPaymentTermsDocument(): ?string
    {
        return $this->getData(self::PAYMENT_TERMS_DOCUMENT);
    }

    /**
     * Setter for PaymentTermsDocument.
     *
     * @param string|null $paymentTermsDocument
     *
     * @return void
     */
    public function setPaymentTermsDocument(?string $paymentTermsDocument): void
    {
        $this->setData(self::PAYMENT_TERMS_DOCUMENT, $paymentTermsDocument);
    }

    /**
     * Getter for PaymentTermsStatus.
     *
     * @return string|null
     */
    public function getPaymentTermsStatus(): ?string
    {
        return $this->getData(self::PAYMENT_TERMS_STATUS);
    }

    /**
     * Setter for PaymentTermsStatus.
     *
     * @param string|null $paymentTermsStatus
     *
     * @return void
     */
    public function setPaymentTermsStatus(?string $paymentTermsStatus): void
    {
        $this->setData(self::PAYMENT_TERMS_STATUS, $paymentTermsStatus);
    }

    /**
     * Getter for CustomersId.
     *
     * @return int|null
     */
    public function getCustomersId(): int
    {
        return $this->getData(self::CUSTOMERS_ID);
    }

    /**
     * Setter for CustomersId.
     *
     * @param int|null $customersId
     *
     * @return void
     */
    public function setCustomersId(?int $customersId): void
    {
        $this->setData(self::CUSTOMERS_ID, $customersId);
    }

    /**
     * Getter for AddressesId.
     *
     * @return int|null
     */
    public function getAddressesId(): ?int
    {
        return $this->getData(self::ADDRESSES_ID) === null ? null
            : (int)$this->getData(self::ADDRESSES_ID);
    }

    /**
     * Setter for AddressesId.
     *
     * @param int|null $addressesId
     *
     * @return void
     */
    public function setAddressesId(?int $addressesId): void
    {
        $this->setData(self::ADDRESSES_ID, $addressesId);
    }

    /**
     * Getter for PaymentTermsAllowedCredit.
     *
     * @return float|null
     */
    public function getPaymentTermsAllowedCredit(): ?float
    {
        return $this->getData(self::PAYMENT_TERMS_ALLOWED_CREDIT) === null ? null
            : (float)$this->getData(self::PAYMENT_TERMS_ALLOWED_CREDIT);
    }

    /**
     * Setter for PaymentTermsAllowedCredit.
     *
     * @param float|null $paymentTermsAllowedCredit
     *
     * @return void
     */
    public function setPaymentTermsAllowedCredit(?float $paymentTermsAllowedCredit): void
    {
        $this->setData(self::PAYMENT_TERMS_ALLOWED_CREDIT, $paymentTermsAllowedCredit);
    }

    /**
     * Getter for PaymentTermsAllowedCreditAvailable.
     *
     * @return float|null
     */
    public function getPaymentTermsAllowedCreditAvailable(): ?float
    {
        return $this->getData(self::PAYMENT_TERMS_ALLOWED_CREDIT_AVAILABLE) === null ? null
            : (float)$this->getData(self::PAYMENT_TERMS_ALLOWED_CREDIT_AVAILABLE);
    }

    /**
     * Setter for PaymentTermsAllowedCreditAvailable.
     *
     * @param float|null $paymentTermsAllowedCreditAvailable
     *
     * @return void
     */
    public function setPaymentTermsAllowedCreditAvailable(?float $paymentTermsAllowedCreditAvailable): void
    {
        $this->setData(self::PAYMENT_TERMS_ALLOWED_CREDIT_AVAILABLE, $paymentTermsAllowedCreditAvailable);
    }

    /**
     * Getter for AcceptedTerms.
     *
     * @return bool|null
     */
    public function getAcceptedTerms(): ?bool
    {
        return $this->getData(self::ACCEPTED_TERMS) === null ? null
            : (bool)$this->getData(self::ACCEPTED_TERMS);
    }

    /**
     * Setter for AcceptedTerms.
     *
     * @param bool|null $acceptedTerms
     *
     * @return void
     */
    public function setAcceptedTerms(?bool $acceptedTerms): void
    {
        $this->setData(self::ACCEPTED_TERMS, $acceptedTerms);
    }

    /**
     * Getter for Ip.
     *
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->getData(self::IP);
    }

    /**
     * Setter for Ip.
     *
     * @param string|null $ip
     *
     * @return void
     */
    public function setIp(?string $ip): void
    {
        $this->setData(self::IP, $ip);
    }

    /**
     * Getter for Date.
     *
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->getData(self::DATE);
    }

    /**
     * Setter for Date.
     *
     * @param string|null $date
     *
     * @return void
     */
    public function setDate(?string $date): void
    {
        $this->setData(self::DATE, $date);
    }

    /**
     * Getter for UserAgent.
     *
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->getData(self::USER_AGENT);
    }

    /**
     * Setter for UserAgent.
     *
     * @param string|null $userAgent
     *
     * @return void
     */
    public function setUserAgent(?string $userAgent): void
    {
        $this->setData(self::USER_AGENT, $userAgent);
    }


    /**
     * @return string|null
     */
    public function getNewCustomerFormDocument(): ?string
    {
        return $this->getData(self::NEW_CUSTOMER_FORM_DOCUMENT);
    }

    /**
     * @param string|null $newCustomerFormDocument
     * @return void
     */
    public function setNewCustomerFormDocument(?string $newCustomerFormDocument): void
    {
        $this->setData(self::NEW_CUSTOMER_FORM_DOCUMENT, $newCustomerFormDocument);
    }

    /**
     * @return string|null
     */
    public function getCreditApplicationDocument(): ?string
    {
        return $this->getData(self::CREDIT_APPLICATION_DOCUMENT);
    }

    /**
     * @param string|null $creditApplicationDocument
     * @return void
     */
    public function setCreditApplicationDocument(?string $creditApplicationDocument): void
    {
        $this->setData(self::CREDIT_APPLICATION_DOCUMENT, $creditApplicationDocument);
    }

    /**
     * @return string|null
     */
    public function getWp9Document(): ?string
    {
        return $this->getData(self::WP9_DOCUMENT);
    }

    /**
     * @param string|null $wp9Document
     * @return void
     */
    public function setWp9Document(?string $wp9Document): void
    {
        $this->setData(self::WP9_DOCUMENT, $wp9Document);
    }

    /**
     * @return string|null
     */
    public function getAccountEmail(): ?string
    {
        return $this->getData(self::ACCOUNT_EMAIL);
    }

    /**
     * @param string|null $accountEmail
     * @return void
     */
    public function setAccountEmail(?string $accountEmail): void
    {
        $this->setData(self::ACCOUNT_EMAIL, $accountEmail);
    }

    /**
     * @return bool
     */
    public function getSavedMiddleware(): bool
    {
        return $this->getData(self::SAVED_MIDDLEWARE);
    }

    /**
     * @param bool $savedMiddleware
     * @return void
     */
    public function setSavedMiddleware(bool $savedMiddleware): void
    {
        $this->setData(self::SAVED_MIDDLEWARE, $savedMiddleware);
    }

    /**
     * @return int|null
     */
    public function getAttempts(): ?int
    {
        return $this->getData(self::ATTEMPTS);
    }

    /**
     * @param int $attempts
     * @return void
     */
    public function setAttempts(int $attempts): void
    {
        $this->setData(self::ATTEMPTS, $attempts);
    }

    /**
     * @return string|null
     */
    public function getResponsibilityAgreementDoc(): ?string
    {
        return $this->getData(self::RESPONSIBILITY_AGREEMENT_DOC);
    }

    /**
     * @param string|null $responsibilityAgreementDoc
     * @return void
     */
    public function setResponsibilityAgreementDoc(?string $responsibilityAgreementDoc): void
    {
        $this->setData(self::RESPONSIBILITY_AGREEMENT_DOC, $responsibilityAgreementDoc);
    }

    /**
     * @return string|null
     */
    public function getCircularFormat170Doc(): ?string
    {
        return $this->getData(self::CIRCULAR_FORMAT_170_DOC);
    }

    /**
     * @param string|null $circularFormat170Doc
     * @return void
     */
    public function setCircularFormat170Doc(?string $circularFormat170Doc): void
    {
        $this->setData(self::CIRCULAR_FORMAT_170_DOC, $circularFormat170Doc);
    }

    /**
     * @return string|null
     */
    public function getPersonalDataProcessingDoc(): ?string
    {
        return $this->getData(self::PERSONAL_DATA_PROCESSING_DOC);
    }

    /**
     * @param string|null $personalDataProcessingDoc
     * @return void
     */
    public function setPersonalDataProcessingDoc(?string $personalDataProcessingDoc): void
    {
        $this->setData(self::PERSONAL_DATA_PROCESSING_DOC, $personalDataProcessingDoc);
    }

    /**
     * @return string|null
     */
    public function getChamberCommerceCertificateDoc(): ?string
    {
        return $this->getData(self::CHAMBER_COMMERCE_CERTIFICATE_DOC);
    }

    /**
     * @param string|null $chamberCommerceCertificateDoc
     * @return void
     */
    public function setChamberCommerceCertificateDoc(?string $chamberCommerceCertificateDoc): void
    {
        $this->setData(self::CHAMBER_COMMERCE_CERTIFICATE_DOC, $chamberCommerceCertificateDoc);
    }

    /**
     * @return string|null
     */
    public function getRutDoc(): ?string
    {
        return $this->getData(self::RUT_DOC);
    }

    /**
     * @param string|null $rutDoc
     * @return void
     */
    public function setRutDoc(?string $rutDoc): void
    {
        $this->setData(self::RUT_DOC, $rutDoc);
    }

    /**
     * @return string|null
     */
    public function getCopyLegalRepresentativeDoc(): ?string
    {
        return $this->getData(self::COPY_LEGAL_REPRESENTATIVE_DOC);
    }

    /**
     * @param string|null $copyLegalRepresentativeDoc
     * @return void
     */
    public function setCopyLegalRepresentativeDoc(?string $copyLegalRepresentativeDoc): void
    {
        $this->setData(self::COPY_LEGAL_REPRESENTATIVE_DOC, $copyLegalRepresentativeDoc);
    }

    /**
     * @return string|null
     */
    public function getCommercialReferenceDoc(): ?string
    {
        return $this->getData(self::COMMERCIAL_REFERENCE_DOC);
    }

    /**
     * @param string|null $commercialReferenceDoc
     * @return void
     */
    public function setCommercialReferenceDoc(?string $commercialReferenceDoc): void
    {
        $this->setData(self::COMMERCIAL_REFERENCE_DOC, $commercialReferenceDoc);
    }

    /**
     * @return string|null
     */
    public function getBankReferenceDoc(): ?string
    {
        return $this->getData(self::BANK_REFERENCE_DOC);
    }

    /**
     * @param string|null $bankReferenceDoc
     * @return void
     */
    public function setBankReferenceDoc(?string $bankReferenceDoc): void
    {
        $this->setData(self::BANK_REFERENCE_DOC, $bankReferenceDoc);
    }

    /**
     * @return string|null
     */
    public function getFinancialStatementsDoc(): ?string
    {
        return $this->getData(self::FINANCIAL_STATEMENTS_DOC);
    }

    /**
     * @param string|null $financialStatementsDoc
     * @return void
     */
    public function setFinancialStatementsDoc(?string $financialStatementsDoc)
    {
        $this->setData(self::FINANCIAL_STATEMENTS_DOC, $financialStatementsDoc);
    }

    /**
     * @return string|null
     */
    public function getBascCertification(): ?string
    {
        return $this->getData(self::BASC_CERTIFICATION);
    }

    /**
     * @param string|null $bascCertification
     * @return void
     */
    public function setBascCertification(?string $bascCertification)
    {
        $this->setData(self::BASC_CERTIFICATION, $bascCertification);
    }

    /**
     * @return string|null
     */
    public function getShareholdingCompCertDoc(): ?string
    {
        return $this->getData(self::SHAREHOLDING_COMP_CERT_DOC);
    }

    /**
     * @param string|null $shareholdingCompCertDoc
     * @return void
     */
    public function setShareholdingCompCertDoc(?string $shareholdingCompCertDoc): void
    {
        $this->setData(self::SHAREHOLDING_COMP_CERT_DOC, $shareholdingCompCertDoc);
    }
}
