<?php

namespace Uzer\Customer\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Uzer\Customer\Api\SaveBusinessInformationInterface;
use Uzer\Customer\Helper\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Uzer\Customer\Api\Data\InformationBusinessInterface;
use Uzer\Customer\Helper\SendEmail;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModel;

class SaveBusinessInformation implements SaveBusinessInformationInterface {

    protected CollectionFactory $collectionFactory;
    protected ResourceModel $informationBusinessResource;
    protected CustomerRepositoryInterface $customerRepository;
    protected SendEmail $sendEmail;
    protected Data $data;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceModel $informationBusinessResource
     * @param CustomerRepositoryInterface $customerRepository
     * @param Data $data
     */
    public function __construct(
            CollectionFactory $collectionFactory,
            ResourceModel $informationBusinessResource,
            CustomerRepositoryInterface $customerRepository,
            SendEmail $sendEmail,
            Data $data
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->informationBusinessResource = $informationBusinessResource;
        $this->customerRepository = $customerRepository;
        $this->data = $data;
        $this->sendEmail = $sendEmail;
    }

    /**
     * @inheritDoc
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(InformationBusinessInterface $informationBusiness): InformationBusinessInterface {
        /** @var InformationBusinessInterface $informationBusinessDb */
        $informationBusinessDb = $this->collectionFactory->create()
                ->addFieldToFilter('customers_id', array('eq' => $informationBusiness->getCustomersId()))
                ->setPageSize(1)
                ->getFirstItem();
        $informationBusinessDb->setCustomersId($informationBusiness->getCustomersId());
        if (!is_null($informationBusiness->getAllowPurchases())) {
            $previews = $informationBusinessDb->getAllowPurchases();
            $informationBusinessDb->setAllowPurchases($informationBusiness->getAllowPurchases());
            $customer = $this->customerRepository->getById($informationBusiness->getCustomersId());
            if ($informationBusiness->getAllowPurchases()) {
                $customerGroupId = $this->data->group();
                $customer->setGroupId($customerGroupId);
            }
            if ($informationBusiness->getAllowPurchases() && $previews != $informationBusiness->getAllowPurchases()) {
                $this->sendEmail->sendEmail($informationBusinessDb, $customer);
            }
            if (!is_null($informationBusiness->getTaxException())) {
                $informationBusinessDb->setTaxException($informationBusiness->getTaxException());
                $informationBusinessDb->setTaxExceptionFrom($informationBusiness->getTaxExceptionFrom());
                $informationBusinessDb->setTaxExceptionTo($informationBusiness->getTaxExceptionTo());
                $customerGroupId = $this->data->getExcludedGroup();
                $customer->setGroupId($customerGroupId);
            }
            $this->customerRepository->save($customer);
        }
        if (!is_null($informationBusiness->getPaymentTerms())) {
            $informationBusinessDb->setPaymentTerms($informationBusiness->getPaymentTerms());
            $informationBusinessDb->setPaymentTermsAllowedCredit($informationBusiness->getPaymentTermsAllowedCredit());
            $informationBusinessDb->setPaymentTermsAllowedCreditAvailable($informationBusiness->getPaymentTermsAllowedCreditAvailable());
        }
        $this->informationBusinessResource->save($informationBusinessDb);
        return $informationBusinessDb;
    }

}
