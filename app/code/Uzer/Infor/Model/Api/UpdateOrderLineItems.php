<?php

namespace Uzer\Infor\Model\Api;

use Uzer\Infor\Helper\Data;
use Magento\Sales\Model\Order;
use GuzzleHttp\ClientFactory;
use Uzer\Infor\Logger\OrderLogger;
use Uzer\Infor\Model\Builder\BuildUpdateOrderItem;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\LocalizedException;

class UpdateOrderLineItems
{

    protected ClientFactory $client;
    protected Data $helper;
    protected OrderLogger $logger;
    protected LoadOrderLineItems $loadOrderLineItems;
    protected BuildUpdateOrderItem $buildUpdateOrderItem;

    public function __construct(
        ClientFactory $client,
        Data $helper,
        OrderLogger $logger,
        LoadOrderLineItems $loadOrderLineItems,
        BuildUpdateOrderItem $buildUpdateOrderItem
    ) {
        $this->client = $client;
        $this->helper = $helper;
        $this->logger = $logger;
        $this->loadOrderLineItems = $loadOrderLineItems;
        $this->buildUpdateOrderItem = $buildUpdateOrderItem;
    }

    public function dispatch(string $token, Order $order, string $stat)
    {
        $url = $this->helper->getApiUrl($order->getStoreId());
        $tenantId = $this->helper->getTenantId($order->getStoreId());
        $ido = $this->helper->getIdo($order->getStoreId());
        $uri = $url . $tenantId . '/CSI/IDORequestService/MGRestService.svc/json/' . 'SLCoitems' . '/updateitems';
        $client = $this->client->create();
        $items = $this->loadOrderLineItems->dispatch($token, $order);
        $lineItems = $items['Items'] ?? [];
        foreach ($lineItems as $item) {
            $lineId = $this->findKey($item, '_ItemId');
            $coNum = $this->findKey($item, 'CoNum');
            $orderModel = $this->buildUpdateOrderItem->build($order, $lineId, $coNum, $stat);
            $data = $orderModel->toArray();
            $this->logger->info('Send Request: ' . json_encode($data));
            try {
                $response = $client->request('PUT', $uri, [
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
            } catch (GuzzleException $e) {
                $this->logger->info('Error: ' . $e->getMessage());
                throw $e;
            }
        }
    }

    private function findKey(array $item, string $key): string
    {
        $result = array_filter($item, function ($value) {
            return $value['Name'] === '_ItemId';
        });
        return !empty($result) ? array_values($result)[0]['Value'] : null;
    }
}
