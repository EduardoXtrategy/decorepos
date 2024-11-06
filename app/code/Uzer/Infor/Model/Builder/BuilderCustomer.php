<?php

namespace Uzer\Infor\Model\Builder;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\LocalizedException;
use Uzer\Customer\Model\CustomerCustomInfo;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\InformationBusinessFactory;
use Uzer\Customer\Model\ResourceModel\InformationBusiness as ResourceModelInformation;
use Uzer\Infor\Api\Data\ModelItemInterface;
use Uzer\Infor\Api\Data\ModelItemInterfaceFactory;
use Uzer\Infor\Api\Data\RequestModelInterface;
use Uzer\Infor\Api\Data\RequestModelInterfaceFactory;

class BuilderCustomer
{

    protected RequestModelInterfaceFactory $customerModelFactory;
    protected ModelItemInterfaceFactory $customerItemFactory;
    protected ResourceModelInformation $informationBusiness;
    protected InformationBusinessFactory $informationBusinessFactory;
    protected CustomerRepositoryInterface $customerRepository;
    protected AddressRepositoryInterface $addressRepository;
    protected CustomerCustomInfo $customerCustomInfo;
    protected Customer $customer;
    protected CustomerInterface $customerInterface;
    protected ?InformationBusiness $informationBusinessModel;
    protected AddressInterface $address;


    /**
     * @param RequestModelInterfaceFactory $customerModelFactory
     * @param ModelItemInterfaceFactory $customerItemFactory
     * @param ResourceModelInformation $informationBusiness
     * @param InformationBusinessFactory $informationBusinessFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AddressRepositoryInterface $addressRepository
     * @param CustomerCustomInfo $customerCustomInfo
     */
    public function __construct(
        RequestModelInterfaceFactory $customerModelFactory,
        ModelItemInterfaceFactory    $customerItemFactory,
        ResourceModelInformation     $informationBusiness,
        InformationBusinessFactory   $informationBusinessFactory,
        CustomerRepositoryInterface  $customerRepository,
        AddressRepositoryInterface   $addressRepository,
        CustomerCustomInfo           $customerCustomInfo
    )
    {
        $this->customerModelFactory = $customerModelFactory;
        $this->customerItemFactory = $customerItemFactory;
        $this->informationBusiness = $informationBusiness;
        $this->addressRepository = $addressRepository;
        $this->informationBusinessFactory = $informationBusinessFactory;
        $this->customerRepository = $customerRepository;
        $this->customerCustomInfo = $customerCustomInfo;
    }


    /**
     *
     *
     * @param Customer $customer
     * @param AddressInterface $address
     * @return RequestModelInterface
     * @throws LocalizedException
     */
    public function build(Customer $customer, AddressInterface $address): RequestModelInterface
    {
        $this->customer = $customer;
        $this->address = $address;
        $this->init();
        $customerModel = $this->customerModelFactory->create();
        $customerModel->setAction(1);
        $customerModel->setItemId('PBT=[ue_DWP_CustAppWrkBnchs]');
        $customerModel->appendProperty($this->buildName());
        $customerModel->appendProperty($this->buildContactName());
        $customerModel->appendProperty($this->buildMagentoId());
        $customerModel->appendProperty($this->buildEmail());
        $customerModel->appendProperty($this->buildAddress());
        $customerModel->appendProperty($this->buildCity());
        $customerModel->appendProperty($this->buildState());
        $customerModel->appendProperty($this->buildCountry());
        $customerModel->appendProperty($this->buildTaxCode());
        $customerModel->appendProperty($this->buildNit());
        $customerModel->appendProperty($this->buildCurrency());
        $customerModel->appendProperty($this->buildPhone());
        $customerModel->appendProperty($this->buildTitle());
        $customerModel->appendProperty($this->buildCreditHome());
        $customerModel->appendProperty($this->buildCreditTerms());
        $customerModel->appendProperty($this->buildCustomerVat());
        $customerModel->appendProperty($this->buildTaxExceptionDate());
        $customerModel->appendProperty($this->buildTaxException());
        $customerModel->appendProperty($this->buildShipToMagentoID());
        $customerModel->appendProperty($this->buildSalesContactEmail());
        $customerModel->appendProperty($this->buildSalesContactPhone());
        return $customerModel;
    }

    /**
     * @throws LocalizedException
     */
    private function init()
    {
        $this->informationBusinessModel = $this->informationBusinessFactory->create();
        $this->informationBusiness->loadByCustomerId($this->informationBusinessModel, $this->customer->getId());
        $this->customerInterface = $this->customerRepository->getById($this->customer->getId());
    }


    /**
     * @throws LocalizedException
     */
    private function buildName(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $company = $this->customerCustomInfo->get($this->customer, 'company_data');
        $customerItem->setModified(true);
        $customerItem->setName('Name');
        if ($company) {
            $customerItem->setIsNull(false);
            $customerItem->setValue($this->customer->getName());
        } else {
            $customerItem->setIsNull(true);
            $customerItem->setValue(null);
        }
        return $customerItem;
    }

    /**
     * @throws LocalizedException
     */
    public function buildContactName(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('Contact');
        $customerItem->setValue($this->customer->getName());
        return $customerItem;
    }

    public function buildMagentoId(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('MagentoId');
        $customerItem->setValue($this->customer->getId());
        return $customerItem;
    }

    public function buildEmail(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('Email');
        $customerItem->setValue($this->customer->getEmail());
        return $customerItem;
    }

    public function buildAddress(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('Address1');
        $customerItem->setIsNull(false);
        $customerItem->setValue(implode(', ', $this->address->getStreet()));
        return $customerItem;
    }

    public function buildCity(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setName('City');
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setValue($this->address->getCity());
        return $customerItem;
    }

    public function buildState(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setName('State');
        $customerItem->setIsNull(false);
        $customerItem->setValue($this->address->getRegionId());
        return $customerItem;
    }

    public function buildCountry(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setName('Country');
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setValue($this->address->getCountryId());
        return $customerItem;
    }

    public function buildTaxCode(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('TaxCode');
        $customerItem->setValue('MAGENT');
        return $customerItem;
    }

    public function buildNit(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('NIT');
        $customerItem->setValue($this->customerInterface->getTaxvat());
        return $customerItem;
    }

    public function buildCurrency(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('Currency');
        $customerItem->setValue('USD');
        return $customerItem;
    }

    public function buildPhone(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('Phone');
        $customerItem->setValue($this->address->getTelephone());
        return $customerItem;
    }

    public function buildTitle(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $title = $this->customerCustomInfo->get($this->customer, 'title_data');
        $customerItem->setName('Title');
        $customerItem->setModified(true);
        if ($title) {
            $customerItem->setIsNull(false);
            $customerItem->setValue($title);
        } else {
            $customerItem->setIsNull(true);
            $customerItem->setValue(null);
        }
        return $customerItem;
    }

    public function buildCreditHome(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('CreditHold');
        $customerItem->setValue('0');
        return $customerItem;
    }

    public function buildCreditTerms(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setName('BillingTerms');
        $customerItem->setModified(true);
        if ($this->informationBusinessModel) {
            $customerItem->setIsNull(false);
            $customerItem->setValue($this->informationBusinessModel->getPaymentTerms() ? '1' : '0');
        } else {
            $customerItem->setIsNull(true);
            $customerItem->setValue(null);
        }
        return $customerItem;
    }

    public function buildCustomerVat(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('CustomerVAT');
        $customerItem->setValue($this->customerInterface->getTaxvat());
        return $customerItem;
    }

    public function buildTaxExceptionDate(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setName('TaxExceptionDate');
        $customerItem->setModified(true);
        if ($this->informationBusinessModel && $this->informationBusinessModel->getTaxException()) {
            $customerItem->setIsNull(false);
            $customerItem->setValue($this->informationBusinessModel->getTaxExceptionTo());
        } else {
            $customerItem->setIsNull(true);
            $customerItem->setValue(null);
        }
        return $customerItem;
    }

    public function buildTaxException(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setName('TaxException');
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setValue($this->informationBusinessModel->getTaxException() ? '1' : '0');
        return $customerItem;
    }

    public function buildShipToMagentoID(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setName('ShipToMagentoID');
        $customerItem->setValue($this->customer->getId());
        return $customerItem;
    }

    public function buildSalesContactEmail(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setName('SalesContactEmail');
        $customerItem->setModified(true);
        if ($this->informationBusinessModel) {
            $customerItem->setIsNull(false);
            $customerItem->setValue($this->informationBusinessModel->getAccountEmail());
        } else {
            $customerItem->setIsNull(true);
            $customerItem->setValue(null);
        }
        return $customerItem;
    }

    public function buildSalesContactPhone(): ModelItemInterface
    {
        $customerItem = $this->customerItemFactory->create();
        $customerItem->setName('SalesContactPhone');
        $customerItem->setModified(true);
        $customerItem->setIsNull(false);
        $customerItem->setValue($this->address->getTelephone());
        return $customerItem;
    }


}
