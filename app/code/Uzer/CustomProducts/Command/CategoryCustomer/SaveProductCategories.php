<?php
namespace Uzer\CustomProducts\Command\CategoryCustomer;

use GuzzleHttp\ClientFactory;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Api\CategoryLinkRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryLinkInterfaceFactory;
use Magento\Catalog\Api\Data\CategoryProductLinkInterface;
use Magento\Catalog\Api\Data\CategoryProductLinkInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductCategoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Uzer\CustomProducts\Model\ProductCategoriesMap;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\UrlInterface;
use Uzer\Core\Logger\Logger;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;

class SaveProductCategories
{

    protected ProductCategoryList $productCategoryList;

    protected CategoryLinkManagementInterface $categoryLinkManagement;

    protected CollectionFactory $productCollectionFactory;

    protected ProductRepositoryInterface $productRepository;

    protected CategoryLinkInterfaceFactory $categoryLinkFactory;

    protected CategoryLinkRepositoryInterface $categoryLinkRepository;

    protected ClientFactory $client;

    protected UrlInterface $url;

    protected Logger $logger;

    /**
     *
     * @param ProductCategoryList $productCategoryList
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param CollectionFactory $productCollectionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryLinkInterfaceFactory $categoryLinkFactory
     * @param CategoryLinkRepositoryInterface $categoryLinkRepository
     */
    public function __construct(ProductCategoryList $productCategoryList, CategoryLinkManagementInterface $categoryLinkManagement, CollectionFactory $productCollectionFactory, ProductRepositoryInterface $productRepository, CategoryLinkInterfaceFactory $categoryLinkFactory, CategoryLinkRepositoryInterface $categoryLinkRepository, ClientFactory $client, UrlInterface $url, Logger $logger)
    {
        $this->productCategoryList = $productCategoryList;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        $this->categoryLinkFactory = $categoryLinkFactory;
        $this->categoryLinkRepository = $categoryLinkRepository;
        $this->client = $client;
        $this->url = $url;
        $this->logger = $logger;
    }

    /**
     *
     * @param ProductCategoriesMap $productCategoriesMap
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws StateException
     */
    public function execute(ProductCategoriesMap $productCategoriesMap): void
    {
        $categories = array_unique($productCategoriesMap->getCategories());
        $categories = $this->removeCategories($categories, [
            56,
            79,
            149
        ]);
        foreach ($categories as $category) {
            $productLink = ObjectManager::getInstance()->create(CategoryProductLinkInterface::class);
            $productLink->setCategoryId($category);
            $productLink->setSku($productCategoriesMap->getSku());
            $productLink->setPosition($productCategoriesMap->getPosition());
            /** @var \Magento\Catalog\Model\ResourceModel\Product $resource */
            $resource = ObjectManager::getInstance()->create(\Magento\Catalog\Model\ResourceModel\Product::class);
            $connection = $resource->getConnection();
            $tableName = $resource->getConnection()->getTableName('catalog_category_product');
            $ids = $resource->getProductsIdsBySkus([
                $productCategoriesMap->getSku()
            ]);
            /** @var CategoryRepositoryInterface $categoryRepository */
            $categoryRepository = ObjectManager::getInstance()->create(CategoryRepositoryInterface::class);
            /** @var CategoryInterface|\Magento\Catalog\Model\Category $categoryEntity */
            $categoryEntity = $categoryRepository->get($category);
            if (! empty($ids)) {
                $productId = array_shift($ids);
                $select = $resource->getConnection()
                    ->select()
                    ->from($tableName)
                    ->where('product_id = ?', $productId)
                    ->where('category_id = ?', $category);
                $result = $connection->fetchRow($select);
                if (empty($result)) {
                    $productPositions = $categoryEntity->getProductsPosition();
                    $productPositions[$productId] = $productLink->getPosition();
                    $categoryEntity->setPostedProducts($productPositions);
                    $categoryEntity->save();
                }
            }
        }
    }

    public function removeCategories(array $categories, array $toRemove = []): array
    {
        foreach ($toRemove as $category) {
            if (in_array($category, $categories)) {
                $index = array_search($category, $categories);
                unset($categories[$index]);
            }
        }
        return $categories;
    }

    public function assignPreviousCategories(ProductCategoriesMap $productCategoriesMap): void
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('sku', $productCategoriesMap->getSku());
        $productCollection->addAttributeToSelect('entity_id')->setPageSize(1);
        /** @var Product $product */
        $product = $productCollection->getFirstItem();
        if ($product->getId()) {
            $categories = $this->productCategoryList->getCategoryIds($product->getId());
            $productCategoriesMap->addCategories($categories);
        }
    }

    public function saveCategories(ProductCategoriesMap $product, array $categories): void
    {
        // $position = date('dhis');
        $client = $this->client->create();
        $url = $this->url->getBaseUrl();
        foreach ($categories as $category) {
            try {
                $finalUrl = "{$url}/rest/all/V1/categories/{$category}/products";
                $finalUrl = str_replace('/mn/', '', $finalUrl);
                $response = $client->post($finalUrl, [
                    'json' => [
                        'productLink' => [
                            'sku' => $product->getSku(),
                            'position' => 0,
                            'category_id' => $category
                        ]
                    ],
                    'headers' => [
                        'Authorization' => 'Bearer padouttr5y8wnefmsd5xvltn0psy2x4s',
                        'Content-Type' => 'application/json'
                    ]
                ]);
                $this->logger->info('Category assigned: ' . $category . ' to product: ' . $product->getSku());
            } catch (\Exception $e) {
                $this->logger->error('Error assigning category: ' . $category . ' to product: ' . $product->getSku() . ' Error: ' . $e->getMessage());
            }
        }
    }
}
