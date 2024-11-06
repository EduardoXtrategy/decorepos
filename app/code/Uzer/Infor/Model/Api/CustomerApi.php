<?php

namespace Uzer\Infor\Model\Api;


use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\LocalizedException;
use Uzer\Infor\Helper\Data;
use Uzer\Infor\Logger\Logger;
use Uzer\Infor\Model\Builder\BuilderCustomer;

class CustomerApi
{

    protected BuilderCustomer $builderCustomer;
    protected ClientFactory $client;
    protected Data $helper;
    protected Logger $logger;

    public function __construct(
        BuilderCustomer $builderCustomer,
        ClientFactory   $client,
        Data            $helper,
        Logger          $logger
    )
    {
        $this->builderCustomer = $builderCustomer;
        $this->client = $client;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @param string $token
     * @param Customer $customer
     * @param AddressInterface $address
     * @param string $addressType
     * @return mixed
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function dispatch(string $token, Customer $customer, AddressInterface $address, string $addressType = 'B')
    {
        $client = $this->client->create();
        $url = $this->helper->getApiUrl($customer->getStoreId());
        $tenantId = $this->helper->getTenantId($customer->getStoreId());
        $ido = $this->helper->getIdo($customer->getStoreId());

        $customerModel = $this->builderCustomer->build($customer, $address, $addressType);
        $data = $customerModel->toArray();
        $this->logger->info('Send Request: ' . json_encode($data));
        $uri = $url . $tenantId . '/CSI/IDORequestService/MGRestService.svc/json/' . $ido . '/additem';
        $this->logger->info('URI: ' . $uri);
        try {
            $response = $client->request('POST', $uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Infor-MongooseConfig' => $this->helper->getMoongooseConfig($customer->getStoreId()),
                ],
                'json' => $data,
            ]);
            $responseBody = json_decode($response->getBody()->getContents(), true);
            $this->logger->info('Response: ' . json_encode($responseBody));
            if ($response->getStatusCode() !== 200) {
                throw new LocalizedException(__($responseBody['message']));
            }
            return $responseBody;
        } catch (GuzzleException $e) {
            $this->logger->info('Error: ' . $e->getMessage());
            throw new $e;
        }
    }

}
