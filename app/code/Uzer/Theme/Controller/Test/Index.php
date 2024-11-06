<?php

namespace Uzer\Theme\Controller\Test;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Index implements HttpGetActionInterface
{
    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $product = ObjectManager::getInstance()->get(Product::class)->load(1238);
        var_dump($product->getBoxQuantity());
        var_dump($product->getMarketingColor());
        var_dump($product->getPerforation());
        var_dump($product->getSku());
        die();
    }
}
