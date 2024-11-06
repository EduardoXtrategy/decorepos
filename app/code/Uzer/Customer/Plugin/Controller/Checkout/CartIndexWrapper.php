<?php

namespace Uzer\Customer\Plugin\Controller\Checkout;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlInterface;

class CartIndexWrapper
{

    protected Session $customerSession;
    protected RedirectFactory $redirectFactory;
    protected RedirectInterface $redirect;
    protected UrlInterface $url;

    /**
     * @param Session $customerSession
     * @param RedirectFactory $redirectFactory
     * @param RedirectInterface $redirect
     * @param UrlInterface $url
     */
    public function __construct(Session $customerSession, RedirectFactory $redirectFactory, RedirectInterface $redirect, UrlInterface $url)
    {
        $this->customerSession = $customerSession;
        $this->redirectFactory = $redirectFactory;
        $this->redirect = $redirect;
        $this->url = $url;
    }


    public function afterExecute(\Magento\Checkout\Controller\Cart\Index $subject, $result)
    {

        if ($this->customerSession->isLoggedIn()) {
            return $result;
        }
        $url = $this->redirect->getRefererUrl();
        $loginUrl = $this->url->getUrl('customer/account/login', array('referer' => base64_encode($url)));
        return $this->redirectFactory->create()->setUrl($loginUrl);
    }

}
