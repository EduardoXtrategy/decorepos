<?php

namespace Uzer\Core\Api;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Customer;
use Uzer\Customer\Model\InformationBusiness;

interface CustomerIntegrationInterface
{

    public function save(Customer $customer, AddressInterface $address, InformationBusiness $informationBusiness);

}
