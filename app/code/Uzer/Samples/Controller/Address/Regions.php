<?php

namespace Uzer\Samples\Controller\Address;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\ResourceModel\AddressFactory as ResourceModel;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Directory\Model\Country;

class Regions implements HttpGetActionInterface
{
    /**
     * @var AddressRepositoryInterface
     */
    private AddressRepositoryInterface $addressRepository;
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    private AddressFactory $addressFactory;
    private ResourceModel $resourceModel;

    protected Country $country;


    /**
     * @param AddressRepositoryInterface $addressRepository
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository,
        RequestInterface $request,
        ResultFactory $resultFactory,
        JsonFactory $resultJsonFactory,
        AddressFactory $addressFactory,
        ResourceModel $resourceModel,
        Country $country
    ) {
        $this->addressRepository = $addressRepository;
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->addressFactory = $addressFactory;
        $this->resourceModel = $resourceModel;
        $this->country = $country;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {           
        $countryCode = $this->request->getParam('code');
        //$countryCode = 'CO';

        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/prueba.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('coigo   '.$countryCode );*/

        $regionCollection = $this->country->loadByCode($countryCode)->getRegions();
        //$regions_list = $regionCollection->loadData()->toOptionArray(false);
                
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson ->setData(['region_address' => $regionCollection->toArray()]);

               
    }
}
