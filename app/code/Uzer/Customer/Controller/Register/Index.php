<?php

namespace Uzer\Customer\Controller\Register;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements HttpGetActionInterface
{

    protected ResultFactory $resultFactory;

    /**
     * @param ResultFactory $resultFactory
     */
    public function __construct(ResultFactory $resultFactory)
    {
        $this->resultFactory = $resultFactory;
    }


    public function execute()
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('customers/register/step');
        return $redirect;
    }
}
