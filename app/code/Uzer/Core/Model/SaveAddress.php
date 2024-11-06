<?php

namespace Uzer\Core\Model;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\RegionInterface;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Directory\Model\RegionFactory;

class SaveAddress
{
    protected AddressInterfaceFactory $addressDataFactory;
    protected AddressRepositoryInterface $addressRepository;
    protected Session $session;
    protected RequestInterface $request;
    protected RegionInterfaceFactory $regionInterfaceFactory;
    protected RegionFactory $regionFactory;

    /**
     * @param AddressInterfaceFactory $addressDataFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param Session $session
     * @param RegionInterfaceFactory $regionInterfaceFactory
     * @param RequestInterface $request
     * @param RegionFactory $regionFactory
     */
    public function __construct(
        AddressInterfaceFactory    $addressDataFactory,
        AddressRepositoryInterface $addressRepository,
        Session                    $session,
        RegionInterfaceFactory     $regionInterfaceFactory,
        RequestInterface           $request,
        RegionFactory              $regionFactory
    )
    {
        $this->addressDataFactory = $addressDataFactory;
        $this->addressRepository = $addressRepository;
        $this->session = $session;
        $this->request = $request;
        $this->regionInterfaceFactory = $regionInterfaceFactory;
        $this->regionFactory = $regionFactory;
    }


    /**
     * @param AddressInterface|null $address
     * @return AddressInterface
     * @throws LocalizedException
     */
    public function execute(?AddressInterface $address): AddressInterface
    {
        if (!$address) {
            $address = $this->addressDataFactory->create();
        }
        $phone = $address->getTelephone();
        $telephone = $this->request->getParam('indicative_telephone') . $this->request->getParam('telephone');
        $telephone = trim($telephone);
        if ($telephone) {
            $phone = $telephone;
        }
        $country = trim($this->request->getParam('country'));
        if ($this->request->getParam('region-sa')) {
            $regionModel = $this->regionFactory->create()->loadByCode($this->request->getParam('region-sa'), $country);
            $region = $this->regionInterfaceFactory->create()
                ->setRegionId($regionModel->getId())
                ->setRegion($regionModel->getName())
                ->setRegionCode($regionModel->getCode());
        } else {
            $region = $this->regionInterfaceFactory->create()->setRegion($this->request->getParam('region'));
        }
        $address->setFirstname($this->request->getParam('first_name'))
            ->setLastname($this->request->getParam('last_name'))
            ->setCountryId($country)
            ->setRegion($region)
            ->setCompany($this->request->getParam('company'))
            ->setCity($this->request->getParam('city'))
            ->setPostcode($this->request->getParam('zip_code'))
            ->setCustomerId($this->session->getCustomerId())
            ->setStreet($this->request->getParam('street'))
            ->setTelephone($phone);
        $this->addressRepository->save($address);
        return $address;
    }
}
