<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModelFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Uzer\Samples\Api\CartInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Customer\Model\Session as customerSession;
use Magento\Framework\Controller\ResultFactory;
use Uzer\Samples\Controller\BaseController;

class Add extends BaseController implements HttpPostActionInterface
{


    private RequestInterface $request;
    private ProductRepositoryInterface $productRepository;
    private CollectionFactory $collectionFactory;
    private ResourceModelFactory $resourceModel;
    private CartInterface $cart;
    private ProductFactory $productFactory;
    private RedirectFactory $redirectFactory;
    private ManagerInterface $manager;
    protected ResultFactory $resultFactory;

    /**
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     * @param CartInterface $cart
     * @param ResourceModelFactory $resourceModel
     * @param ProductFactory $productFactory
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $manager
     * @param CollectionFactory $collectionFactory
     * @param customerSession $customerSession
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        RequestInterface           $request,
        ProductRepositoryInterface $productRepository,
        CartInterface              $cart,
        ResourceModelFactory       $resourceModel,
        ProductFactory             $productFactory,
        RedirectFactory            $redirectFactory,
        ManagerInterface           $manager,
        CollectionFactory          $collectionFactory,
        customerSession            $customerSession,
        ResultFactory              $resultFactory
    )
    {
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->resourceModel = $resourceModel;
        $this->productFactory = $productFactory;
        $this->redirectFactory = $redirectFactory;
        $this->manager = $manager;
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->resultFactory = $resultFactory;
    }

    public function getSessionCustomer()
    {

        return $this->customerSession->isLoggedIn();
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        if (!$this->getSessionCustomer()) {
            return $this->returnLogin();
        }
        $attributes = $this->request->getParam('attribute', array());
        $product = $this->productFactory->create();
        $resourceModel = $this->resourceModel->create();
        $resourceModel->load($product, $this->request->getParam('parent_product_id'));
        if ($product->getTypeId() == 'simple') {
            $this->cart->add($product, NULL, array());
            return $this->redirectFactory->create()->setRefererUrl();
        }
        $keys = array_keys($attributes);
        try {
            $_children = $product->getTypeInstance()->getUsedProducts($product);
            $toAddProducts = array();
            /** @var Product $child */
            foreach ($_children as $child) {
                $toAdd = true;
                foreach ($keys as $key) {
                    $attribute = $child->getCustomAttribute($key);
                    if (!$attribute || !in_array($attribute->getValue(), $attributes[$key])) {
                        $toAdd = false;
                    }
                }
                if ($toAdd) {
                    $toAddProducts[] = $child;
                }
            }
            if (count($toAddProducts) > 0) {
                $this->cart->add($product, $product->getSku(), array());
                foreach ($toAddProducts as $toAddProduct) {
                    $this->cart->add($toAddProduct, $product->getSku(), array());
                }
                $this->manager->addSuccessMessage(__('The %1 product has been added to the sample cart successfully', [$product->getName()]));
            } else {
                $this->manager->addErrorMessage(__('The product selection is invalid, please try again'));
                return $this->redirectFactory->create()->setRefererUrl();
            }
        } catch (\Exception $ex) {
            $this->manager->addErrorMessage(__('An error has occurred, please try again'));
            return $this->redirectFactory->create()->setRefererUrl();
        }
        return $this->redirectFactory->create()->setPath('samples/cart');
    }
}
