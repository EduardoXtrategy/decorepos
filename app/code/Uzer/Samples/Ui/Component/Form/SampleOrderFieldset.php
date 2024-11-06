<?php

namespace Uzer\Samples\Ui\Component\Form;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form\FieldFactory;
use Magento\Ui\Component\Form\Fieldset;
use Uzer\Samples\Model\ResourceModel\SampleOrderFactory as ResourceModelFactory;
use Uzer\Samples\Model\SampleOrder;
use Uzer\Samples\Model\SampleOrderFactory;

class SampleOrderFieldset extends Fieldset
{

    protected FieldFactory $fieldFactory;
    protected ResourceModelFactory $resourceModelFactory;
    protected SampleOrderFactory $sampleOrderFactory;
    protected AddressRepositoryInterface $addressRepository;
    protected CountryInformationAcquirerInterface $countryInformationAcquirer;
    protected ?SampleOrder $sampleOrder = null;

    /**
     * @param ContextInterface $context
     * @param FieldFactory $fieldFactory
     * @param ResourceModelFactory $resourceModelFactory
     * @param SampleOrderFactory $sampleOrderFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param CountryInformationAcquirerInterface $countryInformationAcquirer
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface                    $context,
        FieldFactory                        $fieldFactory,
        ResourceModelFactory                $resourceModelFactory,
        SampleOrderFactory                  $sampleOrderFactory,
        AddressRepositoryInterface          $addressRepository,
        CountryInformationAcquirerInterface $countryInformationAcquirer,
        array                               $components = [],
        array                               $data = []
    )
    {
        parent::__construct($context, $components, $data);
        $this->fieldFactory = $fieldFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->sampleOrderFactory = $sampleOrderFactory;
        $this->addressRepository = $addressRepository;
        $this->countryInformationAcquirer = $countryInformationAcquirer;
    }


    public function getChildComponents(): array
    {
        $components = parent::getChildComponents();
        $entityId = $this->context->getRequestParam('entity_id');
        if ($entityId && is_null($this->sampleOrder)) {
            $this->sampleOrder = $this->sampleOrderFactory->create();
            $this->resourceModelFactory->create()->load($this->sampleOrder, $entityId);
            try {
                $address = $this->addressRepository->getById($this->sampleOrder->getCustomerAddressId());
                $country = $this->countryInformationAcquirer->getCountryInfo($address->getCountryId());
                $region = $address->getRegion()->getRegion();
                $this->setValue($components, 'country', $country->getFullNameEnglish());
                $this->setValue($components, 'region', $region);
                $this->setValue($components, 'city', $address->getCity());
                $this->setValue($components, 'address', implode(', ', $address->getStreet()));
                $this->setValue($components, 'company', $address->getCompany());
                $this->setValue($components, 'first_name', $address->getFirstname());
                $this->setValue($components, 'last_name', $address->getLastname());
                $this->setValue($components, 'phone', $address->getTelephone());
                $this->setValue($components, 'zip_code', $address->getPostcode());
            } catch (\Exception $ex) {

            }
        }
        return $components;
    }

    /**
     * @param UiComponentInterface[] $components
     * @param string $item
     * @param $value
     * @return void
     */
    public function setValue(array $components, string $item, $value)
    {
        $fieldContent = $components[$item];
        $data = $fieldContent->getData();
        $data['config']['value'] = $value;
        $fieldContent->setData($data);
    }
}
