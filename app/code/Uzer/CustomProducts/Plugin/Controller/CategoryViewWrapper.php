<?php

namespace Uzer\CustomProducts\Plugin\Controller;

use Magento\Catalog\Controller\Category\View;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Uzer\CustomProducts\Model\CategoryCustomer;
use Uzer\CustomProducts\Model\ResourceModel\CategoryCustomer\CollectionFactory;
use Magento\Framework\Controller\Result\RedirectFactory;

class CategoryViewWrapper
{
    protected CollectionFactory $collectionFactory;
    protected Session $session;
    protected RedirectFactory $redirectFactory;
    protected RequestInterface $request;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Session $session
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Session           $session,
        RedirectFactory   $redirectFactory,
        RequestInterface  $request)
    {
        $this->collectionFactory = $collectionFactory;
        $this->session = $session;
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
    }

    public function aroundExecute(View $subject, callable $proceed)
    {
        if ($this->redirectToHome()) {
            $resultRedirect = $this->redirectFactory->create();
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }
        return $proceed();
    }

    protected function redirectToHome(): bool
    {
        $id = $this->request->getParam('id');
        /** @var CategoryCustomer $item */
        $item = $this->collectionFactory->create()->addFieldToFilter('category_id', array('eq' => $id))->load()->getFirstItem();
        if ($item->hasData()) {
            return $this->session->getCustomerId() != $item->getCustomerId();
        }
        return false;
    }
}
