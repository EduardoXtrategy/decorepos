<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Out of Stock Notification for Magento 2
 */

namespace Amasty\Xnotif\Model;

use Amasty\Xnotif\Model\Analytics\Collector;
use Amasty\Xnotif\Model\Email\ErrorEmailSender;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\ProductAlert\Model\Email;
use Magento\Store\Api\Data\WebsiteInterface;
use Magento\Store\Model\App\Emulation;

class Observer
{
    /**
     * Warning (exception) errors array
     *
     * @var array
     */
    private $errors = [];

    /**
     * @var array
     */
    private $alertFactories = [];

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Amasty\Xnotif\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    private $configurableType;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var AdminNotification
     */
    private $adminNotification;

    /**
     * @var Collector
     */
    private $collector;

    /**
     * @var Emulation
     */
    private $appEmulation;

    /**
     * @var array
     */
    private $productSendCache = [];

    /**
     * @var \Amasty\Xnotif\Helper\Config
     */
    private $config;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Catalog\Helper\Data
     */
    private $catalogData;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     */
    private $dateFactory;

    /**
     * @var \Magento\ProductAlert\Model\EmailFactory
     */
    private $emailFactory;

    /**
     * @var ErrorEmailSender
     */
    private $errorEmailSender;

    public function __construct(
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\ProductAlert\Model\ResourceModel\Price\CollectionFactory $priceColFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory,
        \Magento\ProductAlert\Model\ResourceModel\Stock\CollectionFactory $stockColFactory,
        \Magento\ProductAlert\Model\EmailFactory $emailFactory,
        \Magento\Framework\Registry $registry,
        \Amasty\Xnotif\Helper\Data $helper,
        \Amasty\Xnotif\Helper\Config $config,
        Configurable $configurableType,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Psr\Log\LoggerInterface $logger,
        AdminNotification $adminNotification,
        Emulation $appEmulation,
        Collector $collector,
        ErrorEmailSender $errorEmailSender
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->catalogData = $catalogData;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->dateFactory = $dateFactory;
        $this->emailFactory = $emailFactory;
        $this->alertFactories = [
            'price' => $priceColFactory,
            'stock' => $stockColFactory
        ];
        $this->customerFactory = $customerFactory;
        $this->registry = $registry;
        $this->helper = $helper;
        $this->configurableType = $configurableType;
        $this->logger = $logger;
        $this->adminNotification = $adminNotification;
        $this->collector = $collector;
        $this->appEmulation = $appEmulation;
        $this->config = $config;
        $this->errorEmailSender = $errorEmailSender;
    }

    /**
     * @return void
     */
    public function process(): void
    {
        $email = $this->emailFactory->create();
        $this->processPrice($email);
        $this->processStock($email);
        $this->sendErrorEmail();
    }

    /**
     * @return void
     */
    public function runDailyCronJob(): void
    {
        $this->sendStockEmailsWithLimit();
        $this->adminNotification->sendAdminNotifications();
    }

    /**
     * @param $type
     * @param Email $email
     *
     * @return $this
     */
    protected function sendNotifications($type, Email $email)
    {
        $prevCustomerEmail = null;
        $prevStoreId = null;
        $email->setType($type);
        $productNotifications = 0;
        $tempNotifications = 0;

        $collection = $this->generateCollectionByType($type);
        foreach ($collection as $alert) {
            try {
                $website = $this->storeManager->getWebsite($alert->getWebsiteId());
                $storeId = $alert->getStoreId() ?: $website->getDefaultStore()->getId();
                $this->appEmulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);
                $this->registerAmastyStore($storeId);
                $email->setWebsite($website)->setStoreId($storeId);

                if (!$this->isAlertEnabled($type, $website)) {
                    $this->appEmulation->stopEnvironmentEmulation();
                    continue;
                }

                $customer = $this->getCustomerFromAlert($alert, $website->getId());
                if (!$customer) {
                    $this->appEmulation->stopEnvironmentEmulation();
                    continue;
                }

                if ($customer->getEmail() !== $prevCustomerEmail ||
                    ($customer->getEmail() === $prevCustomerEmail && $prevStoreId != $alert->getStoreId())
                ) {
                    if ($prevCustomerEmail) {
                        $this->appEmulation->stopEnvironmentEmulation();
                        $this->appEmulation->startEnvironmentEmulation($prevStoreId, Area::AREA_FRONTEND, true);
                        $email->setStoreId($prevStoreId);
                        $email->send();
                        $productNotifications += $tempNotifications;
                        $tempNotifications = 0;
                        $this->deleteTemporaryEmail();
                    }

                    $email->clean();
                    $email->setCustomerData($customer);
                }

                $prevCustomerEmail = $customer->getEmail();
                $prevStoreId = $alert->getStoreId();
                if (!$customer->getId()) {
                    $this->saveTemporaryEmail($prevCustomerEmail);
                }

                $product = $this->loadProduct($alert->getProductId(), $storeId);

                if (!$product) {
                    $this->appEmulation->stopEnvironmentEmulation();
                    continue;
                }

                if ('stock' == $type) {
                    if ($this->shouldSkipByLimit($product, $website)) {
                        $this->appEmulation->stopEnvironmentEmulation();
                        continue;
                    }

                    if ($product = $this->checkStockSubscription($product, $alert, $website)) {
                        $product->setCustomerGroupId($customer->getGroupId());
                        $email->addStockProduct($product);
                        $tempNotifications++;
                    }
                } else {
                    if ($product = $this->checkPriceSubscription($product, $alert)) {
                        $email->addPriceProduct($product);
                    }
                }
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
                $tempNotifications = 0;
            }
            $this->appEmulation->stopEnvironmentEmulation();
        }

        if ($prevCustomerEmail) {
            try {
                $this->appEmulation->startEnvironmentEmulation($prevStoreId, Area::AREA_FRONTEND, true);
                $email->setStoreId($prevStoreId);
                $email->send();
                $productNotifications += $tempNotifications;
                $this->appEmulation->stopEnvironmentEmulation();
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        if ($productNotifications) {
            $this->collector->updateDaily(Collector::ACTION_SENT, $productNotifications);
        }

        return $this;
    }

    protected function shouldSkipByLimit(ProductInterface $product, WebsiteInterface $website): bool
    {
        $result = false;
        if ($this->config->isQtyLimitEnabled()) {
            $productId = $product->getId();
            if (isset($this->productSendCache[$productId])) {
                if ($this->productSendCache[$productId]['qty'] <= $this->productSendCache[$productId]['counter']) {
                    //limit- should skip next
                    $result = true;
                } else {
                    $this->productSendCache[$productId]['counter']++;
                }
            } else {
                $websiteId = (int)$website->getId();
                $this->productSendCache[$productId] = [
                    'qty' => $this->isSalable($product, $website)
                        ? $this->getProductQtyForLimit($product, $websiteId) : 0,
                    'counter' => 1
                ];
            }
        }

        return $result;
    }

    private function getProductQtyForLimit(ProductInterface $product, int $websiteId): int
    {
        $usedProducts = $this->getUsedProducts($product);

        $quantity = array_reduce(
            $usedProducts,
            function ($sum, $item) use ($websiteId) {
                $qty = $this->helper->getProductQty($item, $websiteId);
                return $qty > 0 ? $sum + $qty : $sum;
            },
            0
        );

        return $quantity;
    }

    /**
     * @param int $productId
     * @param int $storeId
     *
     * @return bool|ProductInterface
     */
    protected function loadProduct($productId, $storeId)
    {
        try {
            $product = $this->productRepository->getById(
                $productId,
                false,
                $storeId
            );
        } catch (\Magento\Framework\Exception\NoSuchEntityException $ex) {
            $product = false;
        }

        if (!$product || $product->getStatus() == Status::STATUS_DISABLED) {
            $product = false;
        }

        return $product;
    }

    /**
     * @param string $type
     * @param WebsiteInterface $website
     *
     * @return bool
     */
    protected function isAlertEnabled($type, WebsiteInterface $website)
    {
        return (bool)$this->scopeConfig->getValue(
            'catalog/productalert/allow_' . $type,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $website->getDefaultGroup()->getDefaultStore()->getId()
        );
    }

    /**
     * @param string $type
     *
     * @return \Magento\ProductAlert\Model\ResourceModel\Stock\Collection|\Magento\ProductAlert\Model\ResourceModel\Price\Collection
     */
    protected function generateCollectionByType($type)
    {
        return $this->alertFactories[$type]->create()
            ->addFieldToFilter('status', 0)
            ->setCustomerOrder();
    }

    /**
     * @param $storeId
     */
    protected function registerAmastyStore($storeId)
    {
        if ($this->registry->registry('amasty_store_id')) {
            $this->registry->unregister('amasty_store_id');
        }
        $this->registry->register('amasty_store_id', $storeId);
    }

    /**
     * @param $product
     * @param \Magento\ProductAlert\Model\Price $alert
     * @return null
     */
    private function checkPriceSubscription($product, $alert)
    {
        if ($alert->getPrice() > $product->getFinalPrice()) {
            $productPrice = $product->getFinalPrice();

            if ($alert->getParentId()
                && $alert->getParentId() != $alert->getProductId()
                && !$product->canConfigure()
            ) {
                $product = $this->loadProduct($alert->getParentId(), $product->getStoreId());
            } else {
                $product->setFinalPrice(
                    $this->catalogData->getTaxPrice(
                        $product,
                        $productPrice
                    )
                );
                $product->setPrice(
                    $this->catalogData->getTaxPrice(
                        $product,
                        $product->getPrice()
                    )
                );
            }

            $alert->setPrice($productPrice);
            $alert->setLastSendDate(
                $this->dateFactory->create()->gmtDate()
            );
            $alert->setSendCount($alert->getSendCount() + 1);
            $alert->setStatus(1);
            $alert->save();

            return $product;
        }

        return null;
    }

    /**
     * @param ProductInterface $product
     * @param \Magento\ProductAlert\Model\Stock $alert
     * @param WebsiteInterface $website
     * @return ProductInterface|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function checkStockSubscription(ProductInterface $product, $alert, WebsiteInterface $website)
    {
        if ($this->getIsInStockProduct($product, $website)) {
            if ($alert->getParentId()
                && $alert->getParentId() != $alert->getProductId()
                && !$product->canConfigure()
            ) {
                $product = $this->loadProduct($alert->getParentId(), $product->getStoreId());
            }

            $alert->setSendDate($this->dateFactory->create()->gmtDate());
            $alert->setSendCount($alert->getSendCount() + 1);
            $alert->setStatus(1);
            $alert->save();

            return $product;
        }

        return null;
    }

    /**
     * @param ProductInterface $product
     * @param WebsiteInterface $website
     *
     * @return bool
     */
    protected function getIsInStockProduct(ProductInterface $product, WebsiteInterface $website)
    {
        $minQuantity = $this->config->getMinQty();

        $isInStock = false;
        $allProducts = $this->getUsedProducts($product);
        if ($allProducts && $product->isSalable()) {
            foreach ($allProducts as $simpleProduct) {
                $quantity = $this->helper->getProductQty($simpleProduct, $website->getId());
                $isInStock = ($this->isSalable($simpleProduct, $website) || $simpleProduct->isSaleable())
                    && $quantity >= $minQuantity;
                if ($isInStock) {
                    break;
                }
            }
        } else {
            $quantity = $this->helper->getProductQty($product, $website->getId());
            $isInStock = $this->isSalable($product, $website) && ($quantity >= $minQuantity);
        }

        return $isInStock;
    }

    /**
     * @param ProductInterface $product
     * @param WebsiteInterface $website
     *
     * @return bool
     */
    private function isSalable(ProductInterface $product, WebsiteInterface $website)
    {
        return isset($this->productSalability) ?
            $this->productSalability->isSalable($product, $website) :
            $product->isSalable();
    }

    /**
     * @param \Magento\ProductAlert\Model\Stock $alert
     * @param $websiteId
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getCustomerFromAlert($alert, $websiteId = null)
    {
        if (!$websiteId) {
            $websiteId = $this->storeManager->getStore()->getWebsite()->getId();
        }

        if ($alert->getCustomerId()) {
            try {
                $customer = $this->customerRepository->getById(
                    $alert->getCustomerId()
                );
            } catch (NoSuchEntityException $noSuchEntityException) {
                return null;
            }
        } else {
            try {
                $customer = $this->customerRepository->get(
                    $alert->getEmail(),
                    $websiteId
                );
            } catch (NoSuchEntityException $e) {
                $customer = $this->createCustomerModel($alert->getEmail(), $websiteId);
            }
        }

        $customer->setStoreId($alert->getStoreId());

        return $customer;
    }

    /**
     * @param string $email
     * @param int $websiteId
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    protected function createCustomerModel($email, $websiteId)
    {
        $customer = $this->customerFactory->create()->getDataModel();
        $customer->setWebsiteId(
            $websiteId
        )->setEmail(
            $email
        )->setLastname(
            $this->config->getCustomerName()
        )->setGroupId(
            0
        )->setId(
            0
        );

        return $customer;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    protected function getTestEmail()
    {
        $emailAddress = $this->config->getTestEmail();
        if (!$emailAddress) {
            throw new LocalizedException(
                __(
                    'Please specify email address: Store -> Configuration -> '
                    . 'Amasty Out of Stock Notification -> Test Stock Notification'
                )
            );
        }

        return $emailAddress;
    }

    /**
     * @param $alert
     * @throws LocalizedException
     */
    public function sendTestNotification($alert)
    {
        $alert->setCustomerId(null)->setEmail($this->getTestEmail());

        /** @var \Magento\ProductAlert\Model\Email  $email */
        $email = $this->emailFactory->create();
        $email->setType('stock');

        $websiteId = $alert->getWebsiteId();
        $websiteId = explode(',', $websiteId);
        $websiteId = $websiteId[0];

        $website = $this->storeManager->getWebsite($websiteId);
        $storeId = $alert->getStoreId() ? $alert->getStoreId() : $website->getDefaultStore()->getId();
        $email->setWebsite($website)->setStoreId($storeId);
        if (!$this->scopeConfig->getValue(
            'catalog/productalert/allow_stock',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $website->getDefaultGroup()->getDefaultStore()->getId()
        )) {
            throw new LocalizedException(
                __('Please enable stock notifications: Store -> Configuration -> Catalog -> Alert')
            );
        }

        $productId = $alert->getProductId();
        if ($alert->getParentId()
            && $alert->getParentId() != $productId
        ) {
            $productId = $alert->getParentId();
        }

        $this->appEmulation->startEnvironmentEmulation($storeId, 'frontend', true);
        $product = $this->loadProduct($productId, $storeId);
        $customer = $this->getCustomerFromAlert($alert, $websiteId);
        $product->setCustomerGroupId($customer->getGroupId());

        $email->addStockProduct($product);
        $email->setCustomerData($customer);
        if (!$customer->getId()) {
            $this->saveTemporaryEmail($customer->getEmail());
        }

        $this->registry->register('xnotif_test_notification', true);
        $email->send();
        $this->registry->unregister('xnotif_test_notification');
        $this->appEmulation->stopEnvironmentEmulation();
    }

    /**
     * Save guest email for current iteration
     * @param string $email
     */
    private function saveTemporaryEmail($email)
    {
        $this->deleteTemporaryEmail();
        $this->registry->register(
            'amxnotif_data',
            [
                'guest' => 1,
                'email' => $email
            ]
        );
    }

    private function deleteTemporaryEmail()
    {
        $this->registry->unregister('amxnotif_data');
    }

    /**
     * @param ProductInterface $product
     *
     * @return ProductInterface[]
     */
    private function getUsedProducts(ProductInterface $product): array
    {
        switch ($product->getTypeId()) {
            case Configurable::TYPE_CODE:
                $result = $this->configurableType->getUsedProducts($product);
                break;
            case Grouped::TYPE_CODE:
                $result = $product->getTypeInstance(true)->getAssociatedProducts($product);
                break;
            case BundleType::TYPE_CODE:
                $result = $product->getTypeInstance(true)->getSelectionsCollection(
                    $product->getTypeInstance(true)->getOptionsIds($product),
                    $product
                )->getItems();
                break;
            default:
                $result = [$product];
        }

        return $result;
    }

    /**
     * @return void
     */
    protected function sendStockEmailsWithLimit()
    {
        /** @var \Magento\ProductAlert\Model\Email $email */
        $email = $this->emailFactory->create();
        $this->sendNotifications('stock', $email);
        $this->sendErrorEmail();
    }

    /**
     * @inheritdoc
     */
    protected function processStock(Email $email)
    {
        if (!$this->config->isQtyLimitEnabled()) {
            $this->sendNotifications('stock', $email);
        }
    }

    /**
     * @inheritdoc
     */
    protected function processPrice(Email $email)
    {
        $this->sendNotifications('price', $email);
    }

    protected function sendErrorEmail()
    {
        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $this->logger->error($error);
            }
        }

        $this->errorEmailSender->execute($this->errors);
        $this->errors = [];
    }
}
