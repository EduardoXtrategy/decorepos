<?php

namespace Uzer\Customer\Plugin\Controller\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

class CreatePost
{
    protected Session $session;
    protected RequestInterface $request;
    protected RedirectFactory $redirectFactory;
    protected ManagerInterface $manager;


    /**
     * @param Session $session
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $manager
     */
    public function __construct(
        Session          $session,
        RequestInterface $request,
        RedirectFactory  $redirectFactory,
        ManagerInterface $manager
    )
    {
        $this->session = $session;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->manager = $manager;
    }

    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $controller
     * @param $result
     * @return mixed
     * @throws SessionException
     */
    public function afterExecute(\Magento\Customer\Controller\Account\CreatePost $controller, $result)
    {
        if ($this->session->authenticate()) {
            $result->setPath('customers/register/step');
        } else {
            $result->setPath('customer/account/login');
        }
        return $result;
    }
}
