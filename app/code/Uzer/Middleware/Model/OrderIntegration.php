<?php

namespace Uzer\Middleware\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Sales\Model\Order;
use Uzer\Middleware\Logger\Logger;
use Uzer\Middleware\Model\ResourceModel\OrderMiddlewareFactory as ResourceModelFactory;
use Uzer\Middleware\Model\ResourceModel\OrderMiddleware\CollectionFactory;

class OrderIntegration implements \Uzer\Core\Api\OrderIntegrationInterface
{

    protected CollectionFactory $collectionFactory;
    protected ResourceModelFactory $resourceModel;
    protected OrderMiddlewareFactory $modelFactory;
    protected OrderApi $orderApi;
    protected MiddlewareAuth $middlewareAuth;
    protected Logger $logger;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceModelFactory $resourceModel
     * @param OrderMiddlewareFactory $modelFactory
     * @param OrderApi $orderApi
     * @param MiddlewareAuth $middlewareAuth
     * @param Logger $logger
     */
    public function __construct(
        CollectionFactory      $collectionFactory,
        ResourceModelFactory   $resourceModel,
        OrderMiddlewareFactory $modelFactory,
        OrderApi               $orderApi,
        MiddlewareAuth         $middlewareAuth,
        Logger                 $logger
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->orderApi = $orderApi;
        $this->middlewareAuth = $middlewareAuth;
        $this->logger = $logger;
    }


    /**
     * @throws AlreadyExistsException|\GuzzleHttp\Exception\GuzzleException
     */
    public function save(Order $order): void
    {
        $orderMiddleware = $this->getOrderMiddleware($order);
        try {
            $this->logger->info('-- Processing order ' . $order->getId() . ' ---');
            $this->logger->info('Auth before send');
            $token = $this->middlewareAuth->auth();
            $this->logger->info('Sending order');
            if ($this->hasSend($orderMiddleware, $order)) {
                $this->orderApi->send($token, $order);
                $orderMiddleware->setQty($orderMiddleware->getQty() + 1);
                $orderMiddleware->setSend(true);
                $this->resourceModel->create()->save($orderMiddleware);
            } else {
                $this->logger->info('the order ' . $order->getId() . ' has been previously processed');
            }
        } catch (\Exception $ex) {
            $contains = str_contains($ex->getMessage(), 'order is already created in the middleware') || str_contains($ex->getMessage(), 'order has been created in the middleware');
            $orderMiddleware->setSend($contains);
            $orderMiddleware->setQty($orderMiddleware->getQty() + 1);
            $this->resourceModel->create()->save($orderMiddleware);
            $this->logger->info('Error saving order in middleware: ' . $ex->getMessage());
        }
    }

    private function hasSend(OrderMiddleware $orderMiddleware, Order $order): bool
    {
        if ($order->getPayment()->getMethod() != 'terms') {
            return true;
        }
        return !$orderMiddleware->getSend();
    }

    public function getOrderMiddleware(Order $order): OrderMiddleware
    {
        /** @var OrderMiddleware $item */
        $item = $this->collectionFactory->create()->addOrderIdFilter($order->getId())->load()->getFirstItem();
        if ($item->hasData()) {
            return $item;
        }
        $item = $this->modelFactory->create();
        $item->setOrderId($order->getId());
        return $item;
    }
}
