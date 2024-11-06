<?php

namespace Uzer\Customer\Observer;

use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\UrlInterface;

class CreatePostObserver implements ObserverInterface
{
    protected RequestInterface $request;
    protected ActionFlag $_actionFlag;
    protected ManagerInterface $messageManager;
    protected SessionManagerInterface $_session;

    protected UrlInterface $_urlManager;
    protected RedirectInterface $redirect;

    /**
     * @param RequestInterface $request
     * @param ActionFlag $_actionFlag
     * @param ManagerInterface $messageManager
     * @param SessionManagerInterface $_session
     * @param UrlInterface $_urlManager
     * @param RedirectInterface $redirect
     */
    public function __construct(
        RequestInterface        $request,
        ActionFlag              $_actionFlag,
        ManagerInterface        $messageManager,
        SessionManagerInterface $session,
        UrlInterface            $_urlManager,
        RedirectInterface       $redirect
    )
    {
        $this->request = $request;
        $this->_actionFlag = $_actionFlag;
        $this->messageManager = $messageManager;
        $this->_session = $session;
        $this->_urlManager = $_urlManager;
        $this->redirect = $redirect;
    }


    public function execute(Observer $observer)
    {
        if (!$this->request->getParam('terms')) {
            /** @var \Magento\Framework\App\Action\Action $controller */
            $controller = $observer->getControllerAction();
            $this->messageManager->addErrorMessage(__('You must accept terms and conditions and personal data processing policy'));
            $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
            $this->_session->setCustomerFormData($controller->getRequest()->getPostValue());
            $url = $this->_urlManager->getUrl('*/*/create', ['_nosecret' => true]);
            $controller->getResponse()->setRedirect($this->redirect->error($url));
        }
        $company = $this->request->getParam('company');
        if (empty($company)) {
            /** @var \Magento\Framework\App\Action\Action $controller */
            $controller = $observer->getControllerAction();
            $this->messageManager->addErrorMessage(__('You must enter the name of the company'));
            $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
            $this->_session->setCustomerFormData($controller->getRequest()->getPostValue());
            $url = $this->_urlManager->getUrl('*/*/create', ['_nosecret' => true]);
            $controller->getResponse()->setRedirect($this->redirect->error($url));
        }
        return $this;
    }
}
