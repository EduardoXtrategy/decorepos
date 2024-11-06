<?php

namespace Uzer\CustomProducts\Command\CategoryCustomer;

use GuzzleHttp\ClientFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Uzer\CustomProducts\Helper\Data;
use Uzer\CustomProducts\Model\CategoryCustomer;
use Uzer\CustomProducts\Model\CategoryCustomerFactory as ModelFactory;
use Uzer\CustomProducts\Model\CustomerProductMap;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomerFactory as ResourceModelFactory;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomer\CollectionFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterfaceFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Api\CategoryListInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class SaveCategoryByCustomer
{

    protected ResourceModelFactory $resourceModelFactory;
    protected ModelFactory $modelFactory;
    protected CollectionFactory $collectionFactory;
    protected CategoryRepositoryInterface $categoryRepository;
    protected CategoryFactory $categoryFactory;
    protected StoreManagerInterface $storeManager;
    protected CategoryInterfaceFactory $categoryInterfaceFactory;
    protected CustomerRepositoryInterface $customerRepository;
    protected CategoryLinkManagementInterface $categoryLinkManagement;
    protected ClientFactory $client;
    protected Data $helperData;
    protected CategoryListInterface $categoryList;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected UrlInterface $url;

    /**
     * @param ResourceModelFactory $resourceModelFactory
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryFactory $categoryFactory
     * @param StoreManagerInterface $storeManager
     * @param CategoryInterfaceFactory $categoryInterfaceFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param ClientFactory $client
     * @param Data $helperData
     */
    public function __construct(
        ResourceModelFactory            $resourceModelFactory,
        ModelFactory                    $modelFactory,
        CollectionFactory               $collectionFactory,
        CategoryRepositoryInterface     $categoryRepository,
        CategoryFactory                 $categoryFactory,
        StoreManagerInterface           $storeManager,
        CategoryInterfaceFactory        $categoryInterfaceFactory,
        CustomerRepositoryInterface     $customerRepository,
        CategoryLinkManagementInterface $categoryLinkManagement,
        ClientFactory                   $client,
        Data                            $helperData,
        SearchCriteriaBuilder           $searchCriteriaBuilder,
        CategoryListInterface           $categoryList,
        UrlInterface                    $url
    ) {
        $this->resourceModelFactory = $resourceModelFactory;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->categoryRepository = $categoryRepository;
        $this->categoryFactory = $categoryFactory;
        $this->storeManager = $storeManager;
        $this->categoryInterfaceFactory = $categoryInterfaceFactory;
        $this->customerRepository = $customerRepository;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->helperData = $helperData;
        $this->client = $client;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->categoryList = $categoryList;
        $this->url = $url;
    }


    /**
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function execute(CustomerProductMap $customerProductMap)
    {
        if (count($customerProductMap->getProducts()) <= 0) {
            return;
        }
        /** @var CategoryCustomer $categoryCustomer */
        $categoryCustomer = $this->collectionFactory->create()->addFieldToFilter('customer_id', array('eq' => $customerProductMap->getCustomerId()))->load()->getFirstItem();
        $parent = $this->helperData->parentCategory($this->storeManager->getStore()->getId());
        if (!$categoryCustomer->hasData() || !$categoryCustomer->getCategoryId()) {
            $customer = $this->customerRepository->getById($customerProductMap->getCustomerId());
            $categoryName = sprintf('%s - %s', $customer->getFirstname(), $customer->getId());
            $previousCategory = $this->findPreviewCategory($categoryName, $parent);
            if ($previousCategory) {
                $categoryCustomer->setCustomerId($customer->getId());
                $categoryCustomer->setCategoryId($previousCategory->getId());
                $this->resourceModelFactory->create()->save($categoryCustomer);
                $customerProductMap->setCategoryId($previousCategory->getId());
                return;
            }
            $result = $this->buildCategory($categoryName, $parent);
            if ($result) {
                $categoryCustomer->setCustomerId($customer->getId());
                $categoryCustomer->setCategoryId($result->id);
                $this->resourceModelFactory->create()->save($categoryCustomer);
                $customerProductMap->setCategoryId($result->id);
            }
        } else {
            $customerProductMap->setCategoryId($categoryCustomer->getCategoryId());
        }
    }

    public function findPreviewCategory(string $name, int $parent)
    {
        $this->searchCriteriaBuilder->addFilter('name', $name);
        $categories = $this->categoryList->getList($this->searchCriteriaBuilder->create())->getItems();
        foreach ($categories as $category) {
            if ($category->getParentId() == $parent) {
                return $category;
            }
        }
        return null;
    }

    public function buildCategory(string $name, int $parent)
    {
        $client = $this->client->create();
        $url = $this->url->getBaseUrl();
        try {
            $finalUrl = "{$url}/rest/all/V1/categories";
            $finalUrl = str_replace('/mn/', '', $finalUrl);
            $response = $client->post($finalUrl, [
                'json' => [
                    'category' => [
                        'parent_id' => $parent,
                        'name' => $name,
                        'is_active' => true,
                        'position' => 1,
                        'include_in_menu' => false
                    ]
                ],
                'headers' => [
                    'Authorization' => 'Bearer padouttr5y8wnefmsd5xvltn0psy2x4s',
                    'Content-Type' => 'application/json',
                ],
            ]);
            return json_decode($response->getBody()->getContents(), false);
        } catch (\Exception $e) {
            return null;
        }
    }
}
