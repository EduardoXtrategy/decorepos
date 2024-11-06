<?php

namespace Uzer\Samples\Controller\Checkout;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

class Success implements HttpGetActionInterface
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
    public function __construct(ResultFactory $resultFactory, PageFactory $resultPageFactory)
    {
        $this->resultFactory = $resultFactory;
        $this->resultPageFactory = $resultPageFactory;
    }


    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
