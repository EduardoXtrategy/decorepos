<?php

namespace Uzer\Catalog\Model;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModelProductFactory;
use Magento\Framework\DataObject\Factory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Uzer\Catalog\Api\Data\PartialBoxInterface;
use Uzer\Catalog\Api\PartialBoxQuoteInterface;
use Uzer\Catalog\Model\ResourceModel\PartialBox as ResourceModel;
use Uzer\Catalog\Model\ResourceModel\PartialBox\CollectionFactory;

class PartialBoxQuote implements PartialBoxQuoteInterface
{

    protected ResourceModelProductFactory $resourceModelProduct;
    protected CartRepositoryInterface $cartRepository;
    protected CollectionFactory $collectionFactory;
    protected ProductFactory $productFactory;
    protected ResourceModel $partialBox;
    protected Factory $objectFactory;

    /**
     * @param ResourceModelProductFactory $resourceModelProduct
     * @param CartRepositoryInterface $cartRepository
     * @param CollectionFactory $collectionFactory
     * @param ProductFactory $productFactory
     * @param ResourceModel $partialBox
     * @param Factory $objectFactory
     */
    public function __construct(
        ResourceModelProductFactory $resourceModelProduct,
        CartRepositoryInterface     $cartRepository,
        CollectionFactory           $collectionFactory,
        ProductFactory              $productFactory,
        ResourceModel               $partialBox,
        Factory                     $objectFactory
    )
    {
        $this->resourceModelProduct = $resourceModelProduct;
        $this->cartRepository = $cartRepository;
        $this->productFactory = $productFactory;
        $this->partialBox = $partialBox;
        $this->objectFactory = $objectFactory;
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function add(PartialBoxInterface $partialBox): PartialBoxInterface
    {
        $product = $this->productFactory->create();
        $this->resourceModelProduct->create()->load($product, $partialBox->getProductId());
        /** @var CartInterface|Quote $cart */
        $cart = $this->cartRepository->get($partialBox->getQuoteId());
        $request = $this->objectFactory->create(['qty' => $partialBox->getQty()]);
        $request->setData('qty', $partialBox->getQty());
        $item = $cart->addProduct($product, $request);
        $partialBox->setQuoteId($cart->getId());
        $partialBox->setProductId($product->getId());
        $partialBox->setQuoteItemId($item->getId());
        $this->partialBox->save($partialBox);
        return $partialBox;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function register(PartialBoxInterface $partialBox): PartialBoxInterface
    {
        $this->partialBox->save($partialBox);
        return $partialBox;
    }

    public function getByQuoteItemId(int $quoteId): ?PartialBoxInterface
    {
        /** @var PartialBoxInterface|\Magento\Framework\DataObject $result */
        $result = $this->collectionFactory->create()->addFieldToFilter('quote_item_id', array('eq' => $quoteId))->load()->getFirstItem();
        if ($result->hasData()) {
            return $result;
        }
        return null;
    }

    public function getByQuiteIdAndProductId(int $quoteId, int $productId): ?PartialBoxInterface
    {
//        var_dump($quoteId, $productId);
        /** @var PartialBoxInterface|\Magento\Framework\DataObject $result */
        $result = $this->collectionFactory->create()
            ->addFieldToFilter('quote_id', array('eq' => $quoteId))
            ->addFieldToFilter('product_id', array('eq' => $productId))
            ->load()
            ->getFirstItem();
        if ($result->hasData()) {
            return $result;
        }
        return null;
    }
}
