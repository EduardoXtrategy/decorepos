<?php

namespace Uzer\Customer\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Helper\View;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\ProductAlert\Block\Email\AbstractEmail;
use Magento\ProductAlert\Block\Email\Price;
use Magento\ProductAlert\Block\Email\Stock;
use Magento\ProductAlert\Helper\Data;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Email extends \Magento\ProductAlert\Model\Email
{
    private CustomerCustomInfo $customerCustomInfo;
    private $customerId;

    public function __construct(
        Context                     $context,
        Registry                    $registry,
        Data                        $productAlertData,
        ScopeConfigInterface        $scopeConfig,
        StoreManagerInterface       $storeManager,
        CustomerRepositoryInterface $customerRepository,
        View                        $customerHelper,
        Emulation                   $appEmulation,
        TransportBuilder            $transportBuilder,
        AbstractResource            $resource = null,
        AbstractDb                  $resourceCollection = null,
        CustomerCustomInfo          $customerCustomInfo,
        array                       $data = []
    )
    {
        parent::__construct(
            $context,
            $registry,
            $productAlertData,
            $scopeConfig,
            $storeManager,
            $customerRepository,
            $customerHelper,
            $appEmulation,
            $transportBuilder,
            $resource,
            $resourceCollection,
            $data
        );
        $this->customerCustomInfo = $customerCustomInfo;
    }


    /**
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws MailException
     * @throws \Exception
     */
    public function send(): bool
    {
        if ($this->_website === null || $this->_customer === null || !$this->isExistDefaultStore()) {
            return false;
        }

        $products = $this->getProducts();
        $templateConfigPath = $this->getTemplateConfigPath();
        if (!in_array($this->_type, ['price', 'stock']) || count($products) === 0 || !$templateConfigPath) {
            return false;
        }

        $storeId = (int)$this->getStoreId() ?: (int)$this->_customer->getStoreId();
        $store = $this->getStore($storeId);

        $this->_appEmulation->startEnvironmentEmulation($storeId);

        $block = $this->getBlock();
        $block->setStore($store)->reset();

        // Add products to the block
        foreach ($products as $product) {
            $product->setCustomerGroupId($this->_customer->getGroupId());
            $block->addProduct($product);
        }

        $templateId = $this->_scopeConfig->getValue(
            $templateConfigPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $alertGrid = $this->_appState->emulateAreaCode(
            Area::AREA_FRONTEND,
            [$block, 'toHtml']
        );
        $this->_appEmulation->stopEnvironmentEmulation();

        $customerName = $this->_customerHelper->getCustomerName($this->_customer);

        $templateVars = $this->getTemplateVars($customerName, $alertGrid);
        $this->_transportBuilder->setTemplateIdentifier(
            $templateId
        )->setTemplateOptions(
            ['area' => Area::AREA_FRONTEND, 'store' => $storeId]
        )->setTemplateVars($templateVars)->setFromByScope(
            $this->_scopeConfig->getValue(
                self::XML_PATH_EMAIL_IDENTITY,
                ScopeInterface::SCOPE_STORE,
                $storeId
            ),
            $storeId
        )->addTo(
            $this->_customer->getEmail(),
            $customerName
        )->getTransport()->sendMessage();

        return true;
    }

    public function getTemplateVars($customerName, $alertGrid): array
    {
        $company = $this->customerCustomInfo->getByData($this->_customer, 'company_data');
        return [
            'customerName' => $customerName,
            'alertGrid' => $alertGrid,
            'company' => $company
        ];
    }

    /**
     * Retrieve the store for the email
     *
     * @param int $storeId
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    private function getStore(int $storeId): StoreInterface
    {
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * Retrieve the block for the email based on type
     *
     * @return Price|Stock
     * @throws LocalizedException
     */
    private function getBlock(): AbstractEmail
    {
        return $this->_type === 'price'
            ? $this->_getPriceBlock()
            : $this->_getStockBlock();
    }

    /**
     * Retrieve the products for the email based on type
     *
     * @return array
     */
    private function getProducts(): array
    {
        return $this->_type === 'price'
            ? $this->_priceProducts
            : $this->_stockProducts;
    }

    /**
     * Retrieve template config path based on type
     *
     * @return string
     */
    private function getTemplateConfigPath(): string
    {
        return $this->_type === 'price'
            ? self::XML_PATH_EMAIL_PRICE_TEMPLATE
            : self::XML_PATH_EMAIL_STOCK_TEMPLATE;
    }

    /**
     * Check if exists default store.
     *
     * @return bool
     */
    private function isExistDefaultStore(): bool
    {
        if (!$this->_website->getDefaultGroup() || !$this->_website->getDefaultGroup()->getDefaultStore()) {
            return false;
        }
        return true;
    }

    /**
     * Set customer by id
     *
     * @param int $customerId
     *
     * @return $this
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function setCustomerId($customerId): Email
    {
        $this->customerId = $customerId;
        $this->_customer = $this->customerRepository->getById($customerId);
        return $this;
    }

}
