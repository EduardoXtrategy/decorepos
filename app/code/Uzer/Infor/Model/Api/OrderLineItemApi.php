<?php

namespace Uzer\Infor\Model\Api;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Uzer\Infor\Helper\Data;
use Uzer\Infor\Logger\OrderLogger;
use Uzer\Infor\Model\Builder\BuilderOrderItem;

class OrderLineItemApi
{

    protected BuilderOrderItem $builderOrderItem;
    protected ClientFactory $client;
    protected Data $helper;
    protected OrderLogger $logger;

    public function __construct(
        BuilderOrderItem $builderOrderItem,
        ClientFactory    $client,
        Data             $helper,
        OrderLogger      $logger
    )
    {
        $this->builderOrderItem = $builderOrderItem;
        $this->client = $client;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    public function dispatch(string $token, Order $order, string $coNum)
    {
        $url = $this->helper->getApiUrl($order->getStoreId());
        $tenantId = $this->helper->getTenantId($order->getStoreId());
        $ido = $this->helper->getIdo($order->getStoreId());
        $uri = $url . $tenantId . '/CSI/IDORequestService/MGRestService.svc/json/' . 'SLCoitems' . '/additem/adv?refresh=PROPS&props=CoNum';
        $client = $this->client->create();
        $this->logger->info('Send order items');
        $this->logger->info('URI: ' . $uri);
        $items = $order->getAllVisibleItems();
        foreach ($items as $lineNumber => $item) {
            $orderModel = $this->builderOrderItem->build($order, $item, ($lineNumber + 1), $coNum);
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
                    throw new LocalizedException(__($responseBody['message']));
                }
                return $responseBody;
            } catch (GuzzleException $e) {
                $this->logger->info('Error: ' . $e->getMessage());
                throw new $e;
            }
        }
    }

}
