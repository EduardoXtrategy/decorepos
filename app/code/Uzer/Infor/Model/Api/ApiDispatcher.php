<?php

namespace Uzer\Infor\Model\Api;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Uzer\Infor\Logger\Logger;
use Uzer\Infor\Logger\OrderLogger;
use Uzer\Infor\Model\Control\CustomerAddressControlService;
use Uzer\Infor\Model\Control\CustomerControlService;

class ApiDispatcher
{

    protected Auth $auth;
    protected CustomerApi $customerApi;
    protected CustomerDocumentsApi $customerDocumentsApi;
    protected OrderApi $orderApi;
    protected OrderLineItemApi $orderLineItemApi;
    protected Logger $logger;
    protected OrderLogger $orderLogger;
    protected OrderApiV2 $orderApiV2;
    protected UpdateOrderLineItems $updateOrderLineItems;
    protected CustomerAddressControlService $customerAddressControl;
    protected CustomerControlService $customerControl;


    /**
     * @param Auth $auth
     * @param CustomerApi $customerApi
     * @param CustomerDocumentsApi $customerDocumentsApi
     * @param OrderApi $orderApi
     * @param OrderLineItemApi $orderLineItemApi
     * @param Logger $logger
     * @param OrderLogger $orderLogger
     * @param OrderApiV2 $orderApiV2
     * @param UpdateOrderLineItems $updateOrderLineItems
     * @param CustomerAddressControlService $customerAddressControl
     * @param CustomerControlService $customerControl
     */
    public function __construct(
        Auth                          $auth,
        CustomerApi                   $customerApi,
        CustomerDocumentsApi          $customerDocumentsApi,
        OrderApi                      $orderApi,
        OrderLineItemApi              $orderLineItemApi,
        Logger                        $logger,
        OrderLogger                   $orderLogger,
        OrderApiV2                    $orderApiV2,
        UpdateOrderLineItems          $updateOrderLineItems,
        CustomerAddressControlService $customerAddressControl,
        CustomerControlService        $customerControl
    )
    {
        $this->auth = $auth;
        $this->customerApi = $customerApi;
        $this->customerDocumentsApi = $customerDocumentsApi;
        $this->orderApi = $orderApi;
        $this->orderLineItemApi = $orderLineItemApi;
        $this->logger = $logger;
        $this->orderLogger = $orderLogger;
        $this->orderApiV2 = $orderApiV2;
        $this->updateOrderLineItems = $updateOrderLineItems;
        $this->customerAddressControl = $customerAddressControl;
        $this->customerControl = $customerControl;
    }

    /**
     * @param Customer $customer
     * @param AddressInterface $address
     * @param string $addressType
     * @return void
     * @throws GuzzleException
     * @throws LocalizedException
     * @throws AlreadyExistsException
     */
    public function customer(Customer $customer, AddressInterface $address, string $addressType = 'B')
    {
        $this->logger->info('');
        $token = $this->auth->getBearerToken($customer->getStoreId());
        $this->customerControl->incrementAttempts($customer->getId());
        $addressControl = $this->customerAddressControl->incrementAttempts($address->getId(), $addressType);
        $addressType = $addressControl->getType();
        $this->customerApi->dispatch($token, $customer, $address, $addressType);
        $this->customerAddressControl->markAsSynced($address->getId());
        $this->customerControl->markAsSynced($customer->getId());
    }

    public function customerDocuments(Customer $customer)
    {
        $token = $this->auth->getBearerToken($customer->getStoreId());
        $this->customerDocumentsApi->dispatch($token, $customer);
    }

    /**
     * @param Order $order
     * @return void
     * @throws GuzzleException
     * @throws LocalizedException
     */

    public function order(Order $order)
    {
        $this->orderLogger->info('');
        $token = $this->auth->getBearerToken($order->getStoreId());
        $result = $this->orderApi->dispatch($token, $order);
        if (isset($result['UpdatedItems'][0]['Properties'][0])) {
            $resultItem = $result['UpdatedItems'][0]['Properties'][0];
            if ($resultItem['Name'] == 'CoNum') {
                $this->orderLineItemApi->dispatch($token, $order, $resultItem['Value']);
            }
        }
    }

    /**
     * @param Order $order
     * @return void
     * @throws LocalizedException|GuzzleException
     */
    public function orderV2(Order $order)
    {
        $this->orderLogger->info('');
        $token = $this->auth->getBearerToken($order->getStoreId());
        $this->orderApiV2->dispatch($token, $order);
    }

    public function updateLineItems(Order $order, string $stat)
    {
        $this->orderLogger->info('');
        $token = $this->auth->getBearerToken($order->getStoreId());
        $this->updateOrderLineItems->dispatch($token, $order, $stat);
    }
}
