<?php

namespace Uzer\Infor\Model\Api;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Uzer\Infor\Helper\Data;
use Uzer\Infor\Logger\OrderLogger;
use Uzer\Infor\Model\Builder\BuilderOrder;

class OrderApi
{

    protected BuilderOrder $orderBuilder;
    protected ClientFactory $client;
    protected Data $helper;
    protected OrderLogger $logger;

    public function __construct(
        BuilderOrder  $orderBuilder,
        ClientFactory $client,
        Data          $helper,
        OrderLogger   $logger
    )
    {
        $this->orderBuilder = $orderBuilder;
        $this->client = $client;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @param string $token
     * @param Order $order
     * @return mixed
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function dispatch(string $token, Order $order)
    {
        $url = $this->helper->getApiUrl($order->getStoreId());
        $tenantId = $this->helper->getTenantId($order->getStoreId());
        $ido = $this->helper->getIdo($order->getStoreId());
        $orderModel = $this->orderBuilder->build($order);
        $data = $orderModel->toArray();
        $this->logger->info('Send order');
        $this->logger->info('Send Request: ' . json_encode($data));
        $uri = $url . $tenantId . '/CSI/IDORequestService/MGRestService.svc/json/' . 'SLCos' . '/additem/adv?refresh=PROPS&props=CoNum';
        $this->logger->info('URI: ' . $uri);
        $client = $this->client->create();
        try {
            $response = $client->request('POST', $uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Infor-MongooseConfig' => $this->helper->getMoongooseConfig($order->getStoreId()),
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
