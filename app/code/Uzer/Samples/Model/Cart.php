<?php

namespace Uzer\Samples\Model;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Session\SessionManagerInterface;
use Uzer\Samples\Api\CartInterface;
use Uzer\Samples\Api\Data\SamplesCartInterfaceFactory;
use Uzer\Samples\Command\SamplesCart\SaveCommand;
use Uzer\Samples\Model\ResourceModel\SampleCartItem\CollectionFactory;
use Uzer\Samples\Model\ResourceModel\SampleCartItemFactory as ResourceModelItem;
use \Uzer\Samples\Model\ResourceModel\SamplesCartFactory as ResourceModel;

class Cart implements CartInterface
{

    private SessionManagerInterface $sessionManager;
    private ResourceModelItem $resourceModel;
    private SamplesCart $samplesCart;
    private SampleCartItemFactory $cartItemFactory;
    protected SaveCommand $saveCommand;
    protected SamplesCartInterfaceFactory $samplesCartInterfaceFactory;
    protected \Magento\Customer\Model\Session $customerSession;
    private Session $session;
    private CollectionFactory $itemsCollection;


    /**
     * @param ResourceModelItem $resourceModel
     * @param CollectionFactory $itemsCollection
     * @param SampleCartItemFactory $cartItemFactory
     * @param SessionManagerInterface $sessionManager
     * @param SaveCommand $saveCommand
     * @param SamplesCartInterfaceFactory $samplesCartInterfaceFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param Session $session
     */
    public function __construct(
        ResourceModelItem               $resourceModel,
        CollectionFactory               $itemsCollection,
        SampleCartItemFactory           $cartItemFactory,
        SessionManagerInterface         $sessionManager,
        SaveCommand                     $saveCommand,
        SamplesCartInterfaceFactory     $samplesCartInterfaceFactory,
        \Magento\Customer\Model\Session $customerSession,
        Session                         $session
    )
    {
        $this->resourceModel = $resourceModel;
        $this->cartItemFactory = $cartItemFactory;
        $this->sessionManager = $sessionManager;
        $this->saveCommand = $saveCommand;
        $this->samplesCartInterfaceFactory = $samplesCartInterfaceFactory;
        $this->customerSession = $customerSession;
        $this->session = $session;
        $this->itemsCollection = $itemsCollection;
    }


    /**
     * @param Product $product
     * @param string|Null $parent
     * @param array $attributes
     * @param int $qty
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function add(Product $product, $parent = null, array $attributes, int $qty = 1)
    {
        $this->samplesCart = $this->session->getSamplesCart();
        $collection = $this->itemsCollection->create();
        /** @var SampleCartItem $item */
        $item = $collection
            ->addFieldToFilter('sku', array('eq' => $product->getSku()))
            ->addFieldToFilter('samples_cart_id', array('eq' => $this->samplesCart->getId()))
            ->load()
            ->getFirstItem();
        if (is_null($item) || !$item->getId()) {
            $item = $this->cartItemFactory->create();
            if ($product->getTypeId() == 'simple') {
                $item->setIsParent($product->getSku() == $parent);
            } else if ($product->getTypeId() == Configurable::TYPE_CODE) {
                $item->setIsParent(true);
            }
            $item->setSku($product->getSku());
            $item->setName($product->getName());
            $item->setParent($parent);
            $item->setProductId($product->getId());
            $item->setAttributes(json_encode($attributes));
            $item->setSamplesCartId($this->samplesCart->getId());
        }
        $item->setQty($qty + $item->getQty());
        $this->resourceModel->create()->save($item);
    }

    /**
     * @param int $cartItemId
     * @throws \Exception
     */
    public function remove(int $cartItemId)
    {
        $cartItem = $this->cartItemFactory->create();
        $resourceModel = $this->resourceModel->create();
        $resourceModel->load($cartItem, $cartItemId);
        $resourceModel->delete($cartItem);
    }

    /**
     * @param SampleCartItem $item
     * @throws \Exception
     */
    public function removeItem(SampleCartItem $item)
    {
        $resourceModel = $this->resourceModel->create();
        $resourceModel->delete($item);
    }

    /**
     * @param int $cartItemId
     * @param $qty
     * @return SampleCartItem
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function update(int $cartItemId, $qty): SampleCartItem
    {
        $cartItem = $this->cartItemFactory->create();
        $resourceModel = $this->resourceModel->create();
        $resourceModel->load($cartItem, $cartItemId);
        $cartItem->setQty($qty);
        $resourceModel->save($cartItem);
        return $cartItem;
    }
}
