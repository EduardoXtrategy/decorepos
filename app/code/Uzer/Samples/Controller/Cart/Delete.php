<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Uzer\Samples\Api\CartInterface;
use Uzer\Samples\Logger\Logger;
use Uzer\Samples\Model\ResourceModel\SampleCartItem\CollectionFactory;
use Uzer\Samples\Model\SampleCartItem;
use Uzer\Samples\Model\Session;

class Delete implements HttpGetActionInterface
{

    private RequestInterface $request;
    private CartInterface $cart;
    private ManagerInterface $manager;
    private RedirectFactory $redirectFactory;
    private CollectionFactory $collectionFactory;
    private Session $session;
    private Logger $logger;

    /**
     * @param RequestInterface $request
     * @param CartInterface $cart
     * @param ManagerInterface $manager
     * @param RedirectFactory $redirectFactory
     * @param Logger $logger
     * @param CollectionFactory $collectionFactory
     * @param Session $session
     */
    public function __construct(
        RequestInterface  $request,
        CartInterface     $cart,
        ManagerInterface  $manager,
        RedirectFactory   $redirectFactory,
        Logger            $logger,
        CollectionFactory $collectionFactory,
        Session           $session
    )
    {
        $this->request = $request;
        $this->cart = $cart;
        $this->manager = $manager;
        $this->redirectFactory = $redirectFactory;
        $this->logger = $logger;
        $this->collectionFactory = $collectionFactory;
        $this->session = $session;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     * @throws \Exception
     */
    public function execute()
    {
        $sku = $this->request->getParam('item');

        /** @var SampleCartItem $item */
        $item = $this->collectionFactory->create()
            ->addFieldToFilter('samples_cart_id', array('eq' => $this->session->getSamplesCart()->getId()))
            ->addFieldToFilter('sku', array('eq' => $sku))
            ->addFieldToFilter('is_parent', array('eq' => '0'))
            ->load()
            ->getFirstItem();

        if (!$item || !$item->getId())
            return $this->redirectFactory->create()->setRefererUrl();

        $parentSku = $item->getParent();
        $this->cart->removeItem($item);

        /** @var SampleCartItem[] $items */
        $items = $this->collectionFactory->create()
            ->addFieldToFilter('samples_cart_id', array('eq' => $this->session->getSamplesCart()->getId()))
            ->addFieldToFilter('parent', array('eq' => $parentSku))
            ->addFieldToFilter('is_parent', array('eq' => 0))
            ->load()
            ->getItems();

        if (count($items) <= 0) {
            /** @var SampleCartItem $item */
            $item = $this->collectionFactory->create()
                ->addFieldToFilter('samples_cart_id', array('eq' => $this->session->getSamplesCart()->getId()))
                ->addFieldToFilter('sku', array('eq' => $parentSku))
                ->addFieldToFilter('is_parent', array('eq' => '1'))
                ->load()
                ->getFirstItem();
            if ($item->getId()) {
                $this->cart->removeItem($item);
            }
        }
        $this->manager->addSuccessMessage(__('The item has been removed'));
        return $this->redirectFactory->create()->setRefererUrl();
    }
}
