<?php

namespace Uzer\Jobs\Controller\Form;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
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


    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
