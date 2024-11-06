<?php

namespace Uzer\Infor\Model\Api;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Uzer\Infor\Helper\Data;
use Uzer\Infor\Logger\OrderLogger;
use Uzer\Infor\Model\Builder\BuildOrderV2;

class OrderApiV2
{

    protected BuildOrderV2 $orderBuilder;
    protected ClientFactory $client;
    protected Data $helper;
    protected OrderLogger $logger;

    public function __construct(
        BuildOrderV2  $orderBuilder,
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
     * @return mixed|true
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function dispatch(string $token, Order $order)
    {
        $url = $this->helper->getApiUrl($order->getStoreId());
        $tenantId = $this->helper->getTenantId($order->getStoreId());
        $ido = $this->helper->getIdo($order->getStoreId());
        $uri = $url . $tenantId . '/CSI/IDORequestService/MGRestService.svc/json/' . 'ue_DWP_MagnetoStgCOs' . '/additem';
        $client = $this->client->create();
        $this->logger->info('Send order');
        $this->logger->info('URI: ' . $uri);
        $items = $order->getAllVisibleItems();
        foreach ($items as $item) {
            $orderModel = $this->orderBuilder->build($order, $item);
            $data = $orderModel->toArray();
            $this->logger->info('Send Request: ' . json_encode($data));
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
                    $this->logger->info('Error: ' . $responseBody['message']);
                    throw new LocalizedException(__($responseBody['message']));
                }
            } catch (GuzzleException $e) {
                $this->logger->info('Error: ' . $e->getMessage());
                throw new $e;
            }
        }
        return true;
    }

}
