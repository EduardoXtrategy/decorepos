<?php

namespace Uzer\Customer\Api\Data;

interface InformationBusinessInterface
{

    const ALLOW_PURCHASES = "allow_purchases";
    const REQUEST_ALLOW_PURCHASES = "request_allow_purchases";
    const TAX_EXCEPTION = "tax_exception";
    const REQUEST_TAX_EXCEPTION = "request_tax_exception";
    const TAX_EXCEPTION_FROM = "tax_exception_from";
    const TAX_EXCEPTION_TO = "tax_exception_to";
    const TAX_EXCEPTION_DOCUMENT = "tax_exception_document";
    const NEW_CUSTOMER_FORM_DOCUMENT = "new_customer_form_document";
    const CREDIT_APPLICATION_DOCUMENT = "credit_application_document";
    const WP9_DOCUMENT = "wp9_document";
    const ACCOUNT_EMAIL = "account_email";
    const PAYMENT_TERMS = "payment_terms";
    const REQUEST_PAYMENT_TERMS = "request_payment_terms";
    const PAYMENT_TERMS_REQUEST_DATE = "payment_terms_request_date";
    const PAYMENT_TERMS_RESPONSE_DATE = "payment_terms_response_date";
    const PAYMENT_TERMS_DOCUMENT = "payment_terms_document";
    const PAYMENT_TERMS_STATUS = "payment_terms_status";
    const CUSTOMERS_ID = "customers_id";
    const ADDRESSES_ID = "addresses_id";
    const PAYMENT_TERMS_ALLOWED_CREDIT = "payment_terms_allowed_credit";
    const PAYMENT_TERMS_ALLOWED_CREDIT_AVAILABLE = "payment_terms_allowed_credit_available";
    const ACCEPTED_TERMS = "accepted_terms";
    const IP = "ip";
    const DATE = "date";
    const USER_AGENT = "user_agent";
    const SAVED_MIDDLEWARE = 'saved_middleware';
    const ATTEMPTS = 'attempts';
    const RESPONSIBILITY_AGREEMENT_DOC = 'responsibility_agreement_doc';
    const CIRCULAR_FORMAT_170_DOC = 'circular_format_170_doc';
    const PERSONAL_DATA_PROCESSING_DOC = 'personal_data_processing_doc';
    const CHAMBER_COMMERCE_CERTIFICATE_DOC = 'chamber_commerce_certificate_doc';
    const RUT_DOC = 'rut_doc';
    const COPY_LEGAL_REPRESENTATIVE_DOC = 'copy_legal_representative_doc';
    const COMMERCIAL_REFERENCE_DOC = 'commercial_reference_doc';
    const BANK_REFERENCE_DOC = 'bank_reference_doc';
    const FINANCIAL_STATEMENTS_DOC = 'financial_statements_doc';
    const BASC_CERTIFICATION = 'basc_certification';
    const SHAREHOLDING_COMP_CERT_DOC = 'shareholding_comp_cert_doc';

    /**
     * Getter for AssistanceAllowed.
     *
     * @return bool|null
     */
    public function getAllowPurchases(): ?bool;

    /**
     * Setter for AssistanceAllowed.
     *
     * @param bool|null $allowPurchases
     *
     * @return void
     */
    public function setAllowPurchases(?bool $allowPurchases): void;

    /**
     * Getter for AssistanceAllowed.
     *
     * @return bool|null
     */
    public function getRequestAllowPurchases(): ?bool;

    /**
     * Setter for AssistanceAllowed.
     *
     * @param bool|null $allowPurchases
     *
     * @return void
     */
    public function setRequestAllowPurchases(?bool $allowPurchases): void;

    /**
     * Getter for TaxException.
     *
     * @return bool|null
     */
    public function getTaxException(): ?bool;

    /**
     * Setter for TaxException.
     *
     * @param bool|null $taxException
     *
     * @return void
     */
    public function setTaxException(?bool $taxException): void;

    /**
     * Getter for AccountEmail.
     *
     * @return string|null
     */
    public function getAccountEmail(): ?string;

    /**
     * Setter for AccountEmail.
     *
     * @param string|null $accountEmail
     *
     * @return void
     */
    public function setAccountEmail(?string $accountEmail): void;

    /**
     * Getter for TaxException.
     *
     * @return bool|null
     */
    public function getRequestTaxException(): ?bool;

    /**
     * Setter for TaxException.
     *
     * @param bool|null $taxException
     *
     * @return void
     */
    public function setRequestTaxException(?bool $taxException): void;

    /**
     * Getter for TaxExceptionFrom.
     *
     * @return string|null
     */
    public function getTaxExceptionFrom(): ?string;

    /**
     * Setter for TaxExceptionFrom.
     *
     * @param string|null $taxExceptionFrom
     *
     * @return void
     */
    public function setTaxExceptionFrom(?string $taxExceptionFrom): void;

    /**
     * Getter for TaxExceptionTo.
     *
     * @return string|null
     */
    public function getTaxExceptionTo(): ?string;

    /**
     * Setter for TaxExceptionTo.
     *
     * @param string|null $taxExceptionTo
     *
     * @return void
     */
    public function setTaxExceptionTo(?string $taxExceptionTo): void;

    /**
     * Getter for TaxExceptionDocument.
     *
     * @return string|null
     */
    public function getTaxExceptionDocument(): ?string;

    /**
     * Setter for TaxExceptionDocument.
     *
     * @param string|null $taxExceptionDocument
     *
     * @return void
     */
    public function setTaxExceptionDocument(?string $taxExceptionDocument): void;

    /**
     * Getter for PaymentTerms.
     *
     * @return bool|null
     */
    public function getPaymentTerms(): ?bool;

    /**
     * Setter for PaymentTerms.
     *
     * @param bool|null $paymentTerms
     *
     * @return void
     */
    public function setPaymentTerms(?bool $paymentTerms): void;

    /**
     * Getter for PaymentTerms.
     *
     * @return bool|null
     */
    public function getRequestPaymentTerms(): ?bool;

    /**
     * Setter for PaymentTerms.
     *
     * @param bool|null $paymentTerms
     *
     * @return void
     */
    public function setRequestPaymentTerms(?bool $paymentTerms): void;

    /**
     * Getter for PaymentTermsRequestDate.
     *
     * @return string|null
     */
    public function getPaymentTermsRequestDate(): ?string;

    /**
     * Setter for PaymentTermsRequestDate.
     *
     * @param string|null $paymentTermsRequestDate
     *
     * @return void
     */
    public function setPaymentTermsRequestDate(?string $paymentTermsRequestDate): void;

    /**
     * Getter for PaymentTermsResponseDate.
     *
     * @return string|null
     */
    public function getPaymentTermsResponseDate(): ?string;

    /**
     * Setter for PaymentTermsResponseDate.
     *
     * @param string|null $paymentTermsResponseDate
     *
     * @return void
     */
    public function setPaymentTermsResponseDate(?string $paymentTermsResponseDate): void;

    /**
     * Getter for PaymentTermsDocument.
     *
     * @return string|null
     */
    public function getPaymentTermsDocument(): ?string;

    /**
     * Setter for PaymentTermsDocument.
     *
     * @param string|null $paymentTermsDocument
     *
     * @return void
     */
    public function setPaymentTermsDocument(?string $paymentTermsDocument): void;

    /**
     * Getter for PaymentTermsDocument.
     *
     * @return string|null
     */
    public function getNewCustomerFormDocument(): ?string;

    /**
     * Setter for PaymentTermsDocument.
     *
     * @param string|null $newCustomerFormDocument
     *
     * @return void
     */
    public function setNewCustomerFormDocument(?string $newCustomerFormDocument): void;

    /**
     * Getter for PaymentTermsDocument.
     *
     * @return string|null
     */
    public function getCreditApplicationDocument(): ?string;

    /**
     * Setter for PaymentTermsDocument.
     *
     * @param string|null $creditApplicationDocument
     *
     * @return void
     */
    public function setCreditApplicationDocument(?string $creditApplicationDocument): void;

    /**
     * Getter for PaymentTermsDocument.
     *
     * @return string|null
     */
    public function getWp9Document(): ?string;

    /**
     * Setter for PaymentTermsDocument.
     *
     * @param string|null $wp9Document
     *
     * @return void
     */
    public function setWp9Document(?string $wp9Document): void;

    /**
     * Getter for PaymentTermsStatus.
     *
     * @return string|null
     */
    public function getPaymentTermsStatus(): ?string;

    /**
     * Setter for PaymentTermsStatus.
     *
     * @param string|null $paymentTermsStatus
     *
     * @return void
     */
    public function setPaymentTermsStatus(?string $paymentTermsStatus): void;

    /**
     * Getter for CustomersId.
     *
     * @return int
     */
    public function getCustomersId(): int;

    /**
     * Setter for CustomersId.
     *
     * @param int $customersId
     *
     * @return void
     */
    public function setCustomersId(int $customersId): void;

    /**
     * Getter for AddressesId.
     *
     * @return int|null
     */
    public function getAddressesId(): ?int;

    /**
     * Setter for AddressesId.
     *
     * @param int|null $addressesId
     *
     * @return void
     */
    public function setAddressesId(?int $addressesId): void;

    /**
     * Getter for PaymentTermsAllowedCredit.
     *
     * @return float|null
     */
    public function getPaymentTermsAllowedCredit(): ?float;

    /**
     * Setter for PaymentTermsAllowedCredit.
     *
     * @param float|null $paymentTermsAllowedCredit
     *
     * @return void
     */
    public function setPaymentTermsAllowedCredit(?float $paymentTermsAllowedCredit): void;

    /**
     * Getter for PaymentTermsAllowedCreditAvailable.
     *
     * @return float|null
     */
    public function getPaymentTermsAllowedCreditAvailable(): ?float;

    /**
     * Setter for PaymentTermsAllowedCreditAvailable.
     *
     * @param float|null $paymentTermsAllowedCreditAvailable
     *
     * @return void
     */
    public function setPaymentTermsAllowedCreditAvailable(?float $paymentTermsAllowedCreditAvailable): void;

    /**
     * Getter for AcceptedTerms.
     *
     * @return bool|null
     */
    public function getAcceptedTerms(): ?bool;

    /**
     * Setter for AcceptedTerms.
     *
     * @param bool|null $acceptedTerms
     *
     * @return void
     */
    public function setAcceptedTerms(?bool $acceptedTerms): void;

    /**
     * Getter for Ip.
     *
     * @return string|null
     */
    public function getIp(): ?string;

    /**
     * Setter for Ip.
     *
     * @param string|null $ip
     *
     * @return void
     */
    public function setIp(?string $ip): void;

    /**
     * Getter for Date.
     *
     * @return string|null
     */
    public function getDate(): ?string;

    /**
     * Setter for Date.
     *
     * @param string|null $date
     *
     * @return void
     */
    public function setDate(?string $date): void;

    /**
     * Getter for UserAgent.
     *
     * @return string|null
     */
    public function getUserAgent(): ?string;

    /**
     * Setter for UserAgent.
     *
     * @param string|null $userAgent
     *
     * @return void
     */
    public function setUserAgent(?string $userAgent): void;

    /**
     * Getter for SavedMiddleware
     * @return bool
     */
    public function getSavedMiddleware(): bool;

    /**
     * Setter for SavedMiddleware
     * @param bool $savedMiddleware
     * @return void
     */
    public function setSavedMiddleware(bool $savedMiddleware): void;

    /**
     * Getter for Attempts
     * @return int|null
     */
    public function getAttempts(): ?int;

    /**
     * Setter for Attempts
     *
     * @param int $attempts
     * @return void
     */
    public function setAttempts(int $attempts): void;

    /**
     * Get the value of responsibility_agreement_doc
     *
     * @return string|null
     */
    public function getResponsibilityAgreementDoc(): ?string;

    /**
     * Set the value of responsibility_agreement_doc
     *
     * @param string|null $responsibilityAgreementDoc
     * @return void
     */
    public function setResponsibilityAgreementDoc(?string $responsibilityAgreementDoc): void;

    /**
     * Get the value of circular_format_170_doc
     *
     * @return string|null
     */
    public function getCircularFormat170Doc(): ?string;

    /**
     * Set the value of circular_format_170_doc
     *
     * @param string|null $circularFormat170Doc
     * @return void
     */
    public function setCircularFormat170Doc(?string $circularFormat170Doc): void;

    /**
     * Get the value of personal_data_processing_doc
     *
     * @return string|null
     */
    public function getPersonalDataProcessingDoc(): ?string;

    /**
     * Set the value of personal_data_processing_doc
     *
     * @param string|null $personalDataProcessingDoc
     * @return void
     */
    public function setPersonalDataProcessingDoc(?string $personalDataProcessingDoc): void;

    /**
     * Get the value of chamber_commerce_certificate_doc
     *
     * @return string|null
     */
    public function getChamberCommerceCertificateDoc(): ?string;

    /**
     * Set the value of chamber_commerce_certificate_doc
     *
     * @param string|null $chamberCommerceCertificateDoc
     * @return void
     */
    public function setChamberCommerceCertificateDoc(?string $chamberCommerceCertificateDoc): void;

    /**
     * Get the value of rut_doc
     *
     * @return string|null
     */
    public function getRutDoc(): ?string;

    /**
     * Set the value of rut_doc
     *
     * @param string|null $rutDoc
     * @return void
     */
    public function setRutDoc(?string $rutDoc): void;

    /**
     * Get the value of copy_legal_representative_doc
     *
     * @return string|null
     */
    public function getCopyLegalRepresentativeDoc(): ?string;

    /**
     * Set the value of copy_legal_representative_doc
     *
     * @param string|null $copyLegalRepresentativeDoc
     * @return void
     */
    public function setCopyLegalRepresentativeDoc(?string $copyLegalRepresentativeDoc): void;

    /**
     * Get the value of commercial_reference_doc
     *
     * @return string|null
     */
    public function getCommercialReferenceDoc(): ?string;

    /**
     * Set the value of commercial_reference_doc
     *
     * @param string|null $commercialReferenceDoc
     * @return void
     */
    public function setCommercialReferenceDoc(?string $commercialReferenceDoc): void;

    /**
     * Get the value of bank_reference_doc
     *
     * @return string|null
     */
    public function getBankReferenceDoc(): ?string;

    /**
     * Set the value of bank_reference_doc
     *
     * @param string|null $bankReferenceDoc
     * @return void
     */
    public function setBankReferenceDoc(?string $bankReferenceDoc): void;

    /**
     * Get the value of financial_statements_doc
     *
     * @return string|null
     */
    public function getFinancialStatementsDoc(): ?string;

    /**
     * Set the value of financial_statements_doc
     *
     * @param string|null $financialStatementsDoc
     * @return void
     */
    public function setFinancialStatementsDoc(?string $financialStatementsDoc);

    /**
     * Get the value of basc_certification
     *
     * @return string|null
     */
    public function getBascCertification(): ?string;

    /**
     * Set the value of basc_certification
     *
     * @param string|null $bascCertification
     * @return void
     */
    public function setBascCertification(?string $bascCertification);

    /**
     * Get the value of shareholding_comp_cert_doc
     *
     * @return string|null
     */
    public function getShareholdingCompCertDoc(): ?string;

    /**
     * Set the value of shareholding_comp_cert_doc
     *
     * @param string|null $shareholdingCompCertDoc
     * @return void
     */
    public function setShareholdingCompCertDoc(?string $shareholdingCompCertDoc): void;
}
