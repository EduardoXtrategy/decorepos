<?php

namespace Uzer\Samples\Controller\Address;

use Laminas\View\Model\ViewModel;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\RegionInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Uzer\Core\Model\SaveAddress;

class Add implements HttpPostActionInterface
{

    protected AddressInterfaceFactory $addressDataFactory;
    protected AddressRepositoryInterface $addressRepository;
    protected RequestInterface $request;
    protected ResultFactory $resultFactory;
    protected SaveAddress $saveAddress;

    /**
     * @param AddressInterfaceFactory $addressDataFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param SaveAddress $saveAddress
     */
    public function __construct(AddressInterfaceFactory $addressDataFactory, AddressRepositoryInterface $addressRepository, RequestInterface $request, ResultFactory $resultFactory, SaveAddress $saveAddress)
    {
        $this->addressDataFactory = $addressDataFactory;
        $this->addressRepository = $addressRepository;
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->saveAddress = $saveAddress;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     * @throws LocalizedException
     */
    public function execute()
    {
        if ($this->request->getParam('key_address') > 0):
            $addressId = $this->request->getParam('key_address');
            $address = $this->addressRepository->getById($addressId);
        else:
            $address = $this->addressDataFactory->create();
        endif;
        $address = $this->saveAddress->execute($address);
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('samples/checkout/index', ['selected' => $address->getId()]);
        return $redirect;
    }
}
