<?php

namespace Uzer\Theme\Observer\Import;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;


class Products implements ObserverInterface
{

    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $bunch = $observer->getBunch();
            foreach ($bunch as $item) {
                if ($item['product_type'] == 'configurable') {
                    $product = $this->productRepository->get($item['sku']);
                    $this->productRepository->save($product);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
