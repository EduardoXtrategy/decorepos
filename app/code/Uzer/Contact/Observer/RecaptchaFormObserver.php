<?php

namespace Uzer\Contact\Observer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;
use Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface;
use Magento\ReCaptchaUi\Model\RequestHandlerInterface;

class RecaptchaFormObserver implements ObserverInterface
{
    protected RedirectInterface $redirect;
    protected UrlInterface $url;
    protected IsCaptchaEnabledInterface $isCaptchaEnabled;
    protected RequestHandlerInterface $requestHandler;


    public function __construct(
        UrlInterface              $url,
        IsCaptchaEnabledInterface $isCaptchaEnabled,
        RequestHandlerInterface   $requestHandler,
        RedirectInterface         $redirect
    )
    {
        $this->url = $url;
        $this->isCaptchaEnabled = $isCaptchaEnabled;
        $this->requestHandler = $requestHandler;
        $this->redirect = $redirect;
    }

    /**
     * @throws \Magento\Framework\Exception\InputException
     */
    public function execute(Observer $observer)
    {
        $key = 'contact_form';
        if ($this->isCaptchaEnabled->isCaptchaEnabledFor($key)) {
            /** @var Action $controller */
            $controller = $observer->getControllerAction();
            $request = $controller->getRequest();
            $response = $controller->getResponse();
            $redirectOnFailureUrl = $this->redirect->getRedirectUrl();
            $this->requestHandler->execute($key, $request, $response, $redirectOnFailureUrl);
        }
    }
}
