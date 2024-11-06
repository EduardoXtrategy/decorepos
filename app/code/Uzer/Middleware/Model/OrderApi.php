<?php

namespace Uzer\Middleware\Model;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Webapi\Rest\Request;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Model\Order;
use Uzer\Customer\Model\CustomerCustomInfo;
use Uzer\Middleware\Logger\Logger;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class OrderApi
{

    protected ApiRequest $apiRequest;
    protected Logger $logger;
    protected CustomerCustomInfo $customerCustomInfo;
    protected SourceRepositoryInterface $sourceRepository;
    protected StockRepositoryInterface $stockRepository;
    protected TransactionRepositoryInterface $transactionRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected CountryFactory $countryFactory;

    /**
     * @param ApiRequest $apiRequest
     * @param Logger $logger
     * @param CustomerCustomInfo $customerCustomInfo
     * @param TransactionRepositoryInterface $transactionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CountryFactory $countryFactory
     */
    public function __construct(
        ApiRequest                     $apiRequest,
        Logger                         $logger,
        CustomerCustomInfo             $customerCustomInfo,
        TransactionRepositoryInterface $transactionRepository,
        SearchCriteriaBuilder          $searchCriteriaBuilder,
        CountryFactory                 $countryFactory
    )
    {
        $this->apiRequest = $apiRequest;
        $this->logger = $logger;
        $this->customerCustomInfo = $customerCustomInfo;
        $this->transactionRepository = $transactionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->countryFactory = $countryFactory;
    }


    /**
     * @param string $token
     * @param Order $order
     * @return false|string
     * @throws \Exception
     */
    public function send(string $token, Order $order)
    {
        $orderData = $this->getOrderData($order);
        $requestData = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Bearer %s', $token),
                'x-username' => 'EMagento',
                'x-origin' => 'Magento'
            ],
            'json' => $orderData,
        ];
        try {
            $this->logger->info('Order request: ' . json_encode($orderData));
            $response = $this->apiRequest->doRequest('order', $requestData, Request::HTTP_METHOD_POST);
        } catch (\Exception $ex) {
            $this->logger->info('Error api Response: ' . $ex->getMessage());
            throw new \RuntimeException($ex->getMessage());
        } catch (GuzzleException $ex) {
            $this->logger->info('Error api Response: ' . $ex->getMessage());
            throw new \RuntimeException($ex->getMessage());
        }
        if ($response->getStatusCode() == 200) {
            $result = json_decode($response->getBody()->getContents(), false);
            if ($result->code != 200) {
                $this->logger->info('Order Request: ' . json_encode($orderData) . ' Response: ' . $response->getBody()->getContents() . ' Code: ' . $response->getStatusCode());
                throw new \RuntimeException($result->message);
            }
            $this->logger->info('Order created: ' . json_encode($orderData) . ' Response: ' . $response->getBody()->getContents());
            return json_encode($response->getBody()->getContents());
        } else {
            $this->logger->info('Order Request: ' . json_encode($orderData) . ' Response: ' . $response->getBody()->getContents() . ' Code: ' . $response->getStatusCode());
            $this->logger->info($response->getReasonPhrase());
            throw new \RuntimeException($response->getStatusCode());
        }
    }


    /**
     * @throws \Exception
     */
    public function getOrderData(Order $order): array
    {
        $date = new \DateTime($order->getCreatedAt());
        $customer = $order->getCustomer();
        $customerInfo = $customer->getDataModel();
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $orderItems = $order->getAllItems();
        $message = $this->getComments($order);
        $items = [];
        $transactionId = $this->getTransactionId($order);
        foreach ($orderItems as $orderItem) {
            if ($orderItem->getProductType() == 'simple') {
                if ($orderItem->getParentItem()) {
                    $parent = $orderItem->getParentItem();
                } else {
                    $parent = $orderItem;
                }
                $items[] = [
                    'magentoParentId' => (int)$parent->getItemId(),
                    'parent' => $parent->getSku(),
                    'magentoChildId' => (int)$orderItem->getId(),
                    'child' => $orderItem->getSku(),
                    'itemStatus' => $orderItem->getStatus(),
                    'originalPrice' => (float)$parent->getOriginalPrice(),
                    'price' => (float)$parent->getBasePrice(),
                    'qtyRequested' => (int)$parent->getQtyOrdered(),
                    'qtyShipping' => (int)$parent->getQtyShipped(),
                    'qtyInvoiced' => (int)$parent->getQtyInvoiced(),
                    'qtyCancelled' => (int)$parent->getQtyCanceled(),
                    'taxName' => 'Avalara',
                    'taxPercent' => (float)$parent->getTaxPercent(),
                    'taxAmount' => (float)$parent->getTaxAmount(),
                    'discountAmount' => (float)$parent->getDiscountAmount(),
                    'subTotal' => (float)$parent->getBasePrice(),
                    'totalAmount' => (float)$parent->getRowTotal()
                ];
            }
        }

        $billingCountry = $this->countryFactory->create()->loadByCode($order->getBillingAddress()->getCountryId());
        $shippingCountry = $this->countryFactory->create()->loadByCode($order->getShippingAddress()->getCountryId());

        return [
            'order' => [
                'orderDetail' => [
                    'magentoOrderId' => (int)$order->getId(),
                    'orderNumber' => $order->getIncrementId(),
                    'orderDate' => $date->format('Y-m-d'),
                    'status' => $order->getStatus(),
                    'subtotal' => (float)$order->getSubtotal(),
                    'taxAmount' => (float)$order->getTaxAmount(),
                    'totalFee' => (float)$order->getPaymentFee() ?? 0,
                    'totalAmount' => (float)$order->getGrandTotal(),
                    'Currency' => $order->getOrderCurrencyCode(),
                    'storeId' => (int)$order->getStore()->getStoreId(),
                    'webSiteId' => (int)$order->getStore()->getWebsiteId(),
                    'comments' => $message,
                    'purchaseOrder' => $order->getData('purchase_order')
                ],
                'customer' => [
                    'magentoCustomerId' => (int)$customer->getId(),
                    'email' => $customer->getEmail(),
                    'companyName' => $this->customerCustomInfo->get($customer, 'company_data'),
                    'firstName' => $customerInfo->getFirstName(),
                    'lastName' => $customerInfo->getLastname(),
                    'address' => [
                        [
                            'magentoAddressId' => (int)$billingAddress->getParentId(),
                            'customerId' => (int)$customer->getId(),
                            'type' => 'B',
                            'principal' => false,
                            'address' => join(',', $billingAddress->getStreet()),
                            'city' => $billingAddress->getCity(),
                            'state' => $billingAddress->getRegionCode(),
                            'zip' => $billingAddress->getPostcode(),
                            'country' => $billingCountry->getName(),
                            'codeCountry' => $billingCountry->getCountryId(),
                            'phone' => $billingAddress->getTelephone(),
                            'fax' => $billingAddress->getFax()
                        ],
                        [
                            'magentoAddressId' => (int)$shippingAddress->getParentId(),
                            'customerId' => (int)$customer->getId(),
                            'type' => 'S',
                            'principal' => false,
                            'address' => join(',', $shippingAddress->getStreet()),
                            'city' => $shippingAddress->getCity(),
                            'state' => $shippingAddress->getRegionCode(),
                            'zip' => $shippingAddress->getPostcode(),
                            'country' => $shippingCountry->getName(),
                            'codeCountry' => $shippingCountry->getCountryId(),
                            'phone' => $shippingAddress->getTelephone(),
                            'fax' => $shippingAddress->getFax()
                        ]
                    ]
                ],
                'items' => [
                    'totalItemCount' => (int)$order->getTotalItemCount(),
                    'totalQtyOrdered' => (int)$order->getTotalQtyOrdered(),
                    'lines' => $items
                ],
                'paymentMethod' => [
                    'method' => $order->getPayment()->getMethod(),
                    'status' => $order->getStatus(),
                    'additionalInformation' => join(',', $order->getPayment()->getAdditionalInformation()),
                    'transactionId' => $transactionId
                ],
                "shippingMethod" => [
                    'code' => $order->getShippingMethod(),
                    'name' => $order->getShippingDescription(),
                    'qty' => (int)$order->getTotalQtyOrdered(),
                    'storePickUp' => '',
                    'shippingCost' => (float)$order->getShippingAmount()
                ]
            ]
        ];
    }

    public function getTransactionId(Order $order): ?string
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $order->getId())
            ->create();
        /** @var \Magento\Sales\Model\ResourceModel\Order\Payment\Transaction\Collection|\Magento\Sales\Api\Data\TransactionSearchResultInterface $transactions */
        $transactions = $this->transactionRepository->getList($searchCriteria);
        /** @var TransactionInterface|\Magento\Sales\Model\Order\Payment\Transaction $item */
        $item = $transactions->load()->getFirstItem();
        $transactions->getItems();
        if ($item && $item->getId()) {
            return $item->getTxnId();
        }
        return null;
    }

    public function getComments(Order $order): string
    {
        $comments = [];
        $history = $order->getVisibleStatusHistory();
        /** @var \Magento\Sales\Model\Order\Status\History $historyItem */
        foreach ($history as $historyItem) {
            $comments[] = $historyItem->getComment();
        }
        return join(' ,', $comments);
    }

}
