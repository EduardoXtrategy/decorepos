<?php

namespace Uzer\Samples\Controller\Cart;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as customerSession;

class Index implements HttpGetActionInterface
{

    /**
     * @var ResultFactory
     */
    protected ResultFactory $resultFactory;
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * Success constructor.
     * @param ResultFactory $resultFactory
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
            ResultFactory $resultFactory, 
            customerSession $customerSession,  
            PageFactory $resultPageFactory)
    {
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function getSessionCustomer(){

        return $this->customerSession->isLoggedIn();        
    }


    public function execute()
    {
        if($this->getSessionCustomer()){
            return $this->resultPageFactory->create();
        }else{
            $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $redirect->setPath('customer/account/login');
            return $redirect;
        }
    }
}
