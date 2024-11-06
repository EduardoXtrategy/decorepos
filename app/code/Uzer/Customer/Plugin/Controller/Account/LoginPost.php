<?php

namespace Uzer\Customer\Plugin\Controller\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\SessionException;

class LoginPost
{
    private Session $session;
    private RequestInterface $request;
    private RedirectFactory $redirectFactory;


    public function __construct(Session $session, RequestInterface $request, RedirectFactory $redirectFactory)
    {
        $this->session = $session;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @param \Magento\Customer\Controller\Account\LoginPost $subject
     * @param \Magento\Framework\Controller\Result\Redirect $result
     * @return \Magento\Framework\Controller\Result\Redirect|mixed
     * @throws SessionException
     */
    public function afterExecute(\Magento\Customer\Controller\Account\LoginPost $subject, $result)
    {
        $result = $this->redirectFactory->create();
        if ($this->session->authenticate()) {
            if (!is_null($this->request->getParam('referer'))) {
                $url = base64_decode($this->request->getParam('referer'));
                if (str_contains($url, 'loginPost')) {
                    $result->setPath('product');
                } else {
                    $result->setUrl($url);
                }
            } else {
                $result->setPath('product');
            }
        } else {
            $result->setPath('customer/account/login');
        }
        return $result;
    }
}
