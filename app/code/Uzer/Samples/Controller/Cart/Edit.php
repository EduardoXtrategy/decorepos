<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Uzer\Samples\Api\CartInterface;
use Uzer\Samples\Logger\Logger;
use Uzer\Samples\Model\Session;

class Edit implements HttpPostActionInterface
{

    private RequestInterface $request;
    private CartInterface $cart;
    private ManagerInterface $manager;
    private RedirectFactory $redirectFactory;
    private Logger $logger;

    /**
     * @param RequestInterface $request
     * @param CartInterface $cart
     * @param ManagerInterface $manager
     * @param RedirectFactory $redirectFactory
     * @param Logger $logger
     */
    public function __construct(
        RequestInterface $request,
        CartInterface    $cart,
        ManagerInterface $manager,
        RedirectFactory  $redirectFactory,
        Logger           $logger
    )
    {
        $this->request = $request;
        $this->cart = $cart;
        $this->manager = $manager;
        $this->redirectFactory = $redirectFactory;
        $this->logger = $logger;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $items = $this->request->getParam('item', array());
        foreach ($items as $key => $item) {
            try {
                if ($item > 50) {
                    $this->manager->addErrorMessage(__('You can only add up to 50 items of the same product'));
                    continue;
                }
                $this->cart->update($key, $item);
            } catch (\Exception $ex) {
                $this->logger->info('Error updating product: ' . $key . '; ' . $ex->getMessage());
            }
        }
        $this->manager->addSuccessMessage(__('The samples cart has been updated'));
        return $this->redirectFactory->create()->setRefererUrl();
    }
}
