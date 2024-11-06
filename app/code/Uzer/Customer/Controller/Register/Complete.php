<?php

namespace Uzer\Customer\Controller\Register;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

class Complete implements HttpGetActionInterface
{

    protected PageFactory $resultPageFactory;
    protected ResultFactory $resultFactory;
    protected Session $session;

    /**
     * @param PageFactory $resultPageFactory
     * @param ResultFactory $resultFactory
     * @param Session $session
     */
    public function __construct(PageFactory $resultPageFactory, ResultFactory $resultFactory, Session $session)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultFactory = $resultFactory;
        $this->session = $session;
    }


    public function execute()
    {
        if (!$this->session->isLoggedIn()) {
            $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $redirect->setPath('customer/account/login');
            return $redirect;
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addPageLayoutHandles([], 'uzer_customer_register_step');
        $resultPage->getConfig()->getTitle()->set(__('Want to shop online with Decowraps?'));
        return $resultPage;
    }
}
