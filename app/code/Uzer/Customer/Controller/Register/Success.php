<?php

namespace Uzer\Customer\Controller\Register;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;

class Success implements HttpGetActionInterface
{

    protected PageFactory $pageFactory;
    protected ResultFactory $resultFactory;
    protected Session $session;

    /**
     * @param PageFactory $pageFactory
     * @param ResultFactory $resultFactory
     * @param Session $session
     */
    public function __construct(PageFactory $pageFactory, ResultFactory $resultFactory, Session $session)
    {
        $this->pageFactory = $pageFactory;
        $this->resultFactory = $resultFactory;
        $this->session = $session;
    }


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        if (!$this->session->isLoggedIn()) {
            $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $redirect->setPath('customer/account/login');
            return $redirect;
        }
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('New "Online shopping" account request submitted'));
        return $resultPage;
    }
}
