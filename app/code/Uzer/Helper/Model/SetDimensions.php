<?php

namespace Uzer\Helper\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetDimensions
{

    protected CollectionFactory $collectionFactory;
    protected ProductFactory $resourceModelFactory;
    protected State $state;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ProductFactory $resourceModelFactory
     */
    public function __construct(CollectionFactory $collectionFactory, ProductFactory $resourceModelFactory, State $state)
    {
        $this->collectionFactory = $collectionFactory;
        $this->resourceModelFactory = $resourceModelFactory;
        $this->state = $state;
    }


    /**
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $time = time();
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $website = ObjectManager::getInstance()->create(WebsiteRepositoryInterface::class)->get('us');
        $resourceModel = $this->resourceModelFactory->create();
        /** @var Product[] $products */
        $products = $this->collectionFactory->create()
            ->addAttributeToFilter('type_id', \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->addWebsiteFilter($website->getId())
            ->addOrder('id', \Magento\Framework\Data\Collection::SORT_ORDER_DESC)
            ->load()->getItems();
        $totalItems = count($products);
        $counter = 0;

        foreach ($products as $product) {
            /** @var Product $product */
            $resourceModel->load($product, $product->getId());
            $height = $product->getData('height');
            $width = $product->getData('width');
            $length = $product->getData('length');
            $product->setShipHeight($height);
            $product->setShipWidth($width);
            $product->setShipLength($length);
            $product->setShipperhqDimGroup(9220);
            $resourceModel->save($product);
            $counter++;
            $time1 = time();
            $totalTime = ($time1 - $time);
            $output->writeln('<info>Saved: ' . $counter . ' of ' . $totalItems . '; ' . $totalTime . ' seconds.</info>');
        }
    }

}
