<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Uzer\Samples\Controller\BaseController;

class Index extends BaseController implements HttpGetActionInterface
{

    protected ResultFactory $resultFactory;
    protected PageFactory $resultPageFactory;
    protected CustomerSession $customerSession;
    private UrlInterface $urlInterface;
    private RedirectFactory $redirectFactory;
    private RedirectInterface $redirect;

    /**
     * Success constructor.
     * @param ResultFactory $resultFactory
     * @param CustomerSession $customerSession
     * @param PageFactory $resultPageFactory
     * @param UrlInterface $urlInterface
     * @param RedirectFactory $redirectFactory
     * @param RedirectInterface $redirect
     */
    public function __construct(
        ResultFactory     $resultFactory,
        CustomerSession   $customerSession,
        PageFactory       $resultPageFactory,
        UrlInterface      $urlInterface,
        RedirectFactory   $redirectFactory,
        RedirectInterface $redirect
    )
    {
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->urlInterface = $urlInterface;
        $this->redirectFactory = $redirectFactory;
        $this->redirect = $redirect;
    }

    public function getSessionCustomer(): bool
    {

        return $this->customerSession->isLoggedIn();
    }

    public function returnLogin(): Redirect
    {
        $url = $this->redirect->getRefererUrl();
        $login_url = $this->urlInterface
            ->getUrl('customer/account/login',
                array('referer' => base64_encode($url))
            );
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setUrl($login_url);
        return $resultRedirect;
    }

    public function execute()
    {
        if ($this->getSessionCustomer()) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->setMetaTitle(__('Samples Cart'));
            return $resultPage;
        } else {
            return $this->returnLogin();
        }
    }
}
