<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Uzer\Samples\Api\CartInterface;
use Uzer\Samples\Model\ResourceModel\SamplesCartFactory;
use Uzer\Samples\Model\Session;

class Clear implements HttpGetActionInterface
{


    private CartInterface $cart;
    private Session $session;
    private SamplesCartFactory $resourceModel;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $manager;

    /**
     * @param CartInterface $cart
     * @param Session $session
     * @param SamplesCartFactory $resourceModel
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $manager
     */
    public function __construct(CartInterface $cart, Session $session, SamplesCartFactory $resourceModel, RedirectFactory $redirectFactory, ManagerInterface $manager)
    {
        $this->cart = $cart;
        $this->session = $session;
        $this->resourceModel = $resourceModel;
        $this->redirectFactory = $redirectFactory;
        $this->manager = $manager;
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\SessionException
     * @throws \Exception
     */
    public function execute()
    {
        $samplesCart = $this->session->getSamplesCart();
        $items = $samplesCart->getItems();
        foreach ($items as $item) {
            $this->cart->removeItem($item);
        }
        $resourceModel = $this->resourceModel->create();
        $resourceModel->delete($samplesCart);
        $this->manager->addSuccessMessage(__('The sample cart has been cleared'));
        return $this->redirectFactory->create()->setRefererUrl();
    }
}
