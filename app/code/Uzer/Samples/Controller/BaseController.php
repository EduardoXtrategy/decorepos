<?php

namespace Uzer\Samples\Controller;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlInterface;

class BaseController
{

    public function returnLogin()
    {
        $urlInterface = ObjectManager::getInstance()->create(UrlInterface::class);
        $redirectFactory = ObjectManager::getInstance()->create(RedirectFactory::class);
        $redirect = ObjectManager::getInstance()->create(RedirectInterface::class);
        $url = $redirect->getRefererUrl();
        $login_url = $urlInterface
            ->getUrl('customer/account/login',
                array('referer' => base64_encode($url))
            );
        $resultRedirect = $redirectFactory->create();
        $resultRedirect->setUrl($login_url);
        return $resultRedirect;
    }
}
