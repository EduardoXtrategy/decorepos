<?php

namespace Uzer\CustomProducts\Controller\Index;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\CustomProducts\Model\CategoryCustomer;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomer\CollectionFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\UrlInterface;

class Index implements HttpGetActionInterface
{


    protected CategoryRepositoryInterface $categoryRepository;
    protected StoreManagerInterface $_storeManager;
    protected ResultFactory $resultFactory;
    protected CustomerSession $customerSession;
    protected RedirectFactory $redirectFactory;
    protected RedirectInterface $redirect;
    protected CategoryUrlPathGenerator $categoryUrlPathGenerator;
    protected CollectionFactory $collectionFactory;
    protected ForwardFactory $forwardFactory;
    protected UrlInterface $url;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $_storeManager
     * @param ResultFactory $resultFactory
     * @param CustomerSession $customerSession
     * @param RedirectFactory $redirectFactory
     * @param RedirectInterface $redirect
     * @param CategoryUrlPathGenerator $categoryUrlPathGenerator
     * @param CollectionFactory $collectionFactory
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterface       $_storeManager,
        ResultFactory               $resultFactory,
        CustomerSession             $customerSession,
        RedirectFactory             $redirectFactory,
        RedirectInterface           $redirect,
        CategoryUrlPathGenerator    $categoryUrlPathGenerator,
        CollectionFactory           $collectionFactory,
        ForwardFactory              $forwardFactory,
        UrlInterface                $url
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->_storeManager = $_storeManager;
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession;
        $this->redirectFactory = $redirectFactory;
        $this->redirect = $redirect;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
        $this->collectionFactory = $collectionFactory;
        $this->forwardFactory = $forwardFactory;
        $this->url = $url;
    }


    /**
     * Execute action based on request and return result
     *
     * @return bool
     * @throws NotFoundException
     */
    public function getSessionCustomer(): bool
    {

        return $this->customerSession->isLoggedIn();
    }

    /**
     * @throws NoSuchEntityException
     * @throws NotFoundException
     */
    public function execute()
    {
        if ($this->getSessionCustomer()) {
            return $this->viewProducts();
        }
        return $this->returnLogin();
    }

    public function returnLogin(): Redirect
    {
        $url = $this->redirect->getRefererUrl();
        $login_url = $this->url
            ->getUrl('customer/account/login',
                array('referer' => base64_encode($url))
            );
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setUrl($login_url);
        return $resultRedirect;
    }


    /**
     * @throws NoSuchEntityException
     */
    public function viewProducts()
    {
        $resultRedirect = $this->redirectFactory->create();
        /** @var CategoryCustomer $categoryCustomer */
        $categoryCustomer = $this->collectionFactory->create()->addFieldToFilter('customer_id', array('eq' => $this->customerSession->getCustomerId()))->load()->getFirstItem();
        if ($categoryCustomer->hasData() && $categoryCustomer->getCategoryId()) {
            /** @var \Magento\Catalog\Model\Category|\Magento\Catalog\Api\Data\CategoryInterface $category */
            $category = $this->categoryRepository->get($categoryCustomer->getCategoryId(), $this->_storeManager->getStore()->getId());
            $resultRedirect->setUrl($category->getUrl());
            return $resultRedirect;
        }
        $resultForward = $this->forwardFactory->create();
        $resultForward->setController('noroute');
        $resultForward->forward('index');
        return $resultForward;
    }
}
