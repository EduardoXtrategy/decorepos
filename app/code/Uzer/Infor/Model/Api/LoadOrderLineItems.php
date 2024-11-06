<?php

namespace Uzer\Infor\Model\Api;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Uzer\Infor\Helper\Data;
use Uzer\Infor\Logger\OrderLogger;

class LoadOrderLineItems
{

    protected ClientFactory $client;
    protected Data $helper;
    protected OrderLogger $logger;

    public function __construct(
        ClientFactory $client,
        Data $helper,
        OrderLogger $logger
    )
    {
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
        $this->logger->info('Send order');
        $uri = $url . $tenantId . "/CSI/IDORequestService/MGRestService.svc/json/SLCoitems/adv?props=CoNum,CoLine,CoRelease,Item&rowcap=50&filter=ue_DerMagentoOrder='{$order->getId()}'";
        $this->logger->info('URI: ' . $uri);
        $client = $this->client->create();
        try {
            $response = $client->request('GET', $uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Infor-MongooseConfig' => $this->helper->getMoongooseConfig($order->getStoreId()),
                ]
            ]);
        } catch (GuzzleException $e) {
            $this->logger->error('Error: ' . $e->getMessage());
            throw new LocalizedException(__('Error: ' . $e->getMessage()));
        }
        $response = json_decode($response->getBody()->getContents(), true);
        $this->logger->info('Response: ' . json_encode($response));
        return $response;
    }
}
