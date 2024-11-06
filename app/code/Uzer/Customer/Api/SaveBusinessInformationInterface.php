<?php

namespace Uzer\Customer\Api;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Uzer\Customer\Api\Data\InformationBusinessInterface;

interface SaveBusinessInformationInterface
{

    /**
     * @param InformationBusinessInterface $informationBusiness
     * @return InformationBusinessInterface
     * @throws AlreadyExistsException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @Api
     */
    public function execute(InformationBusinessInterface $informationBusiness): InformationBusinessInterface;

}
