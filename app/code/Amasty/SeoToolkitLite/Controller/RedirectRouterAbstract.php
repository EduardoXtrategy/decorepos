<?php

declare(strict_types=1);

namespace Amasty\SeoToolkitLite\Controller;

use Amasty\SeoToolkitLite\Api\Data\RedirectInterface;
use Amasty\SeoToolkitLite\Model\Redirect\RedirectGetter;
use Amasty\SeoToolkitLite\Model\Redirect\TargetPathResolver;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;

class RedirectRouterAbstract implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var RedirectGetter
     */
    private $redirectGetter;

    /**
     * @var TargetPathResolver
     */
    private $targetPathResolver;

    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        RedirectGetter $redirectGetter,
        TargetPathResolver $targetPathResolver
    ) {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->redirectGetter = $redirectGetter;
        $this->targetPathResolver = $targetPathResolver;
    }

    /**
     * @param RequestInterface $request
     * @return bool|\Magento\Framework\App\ActionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function match(RequestInterface $request)
    {
        $path = $request->getOriginalPathInfo();
        $redirect = $this->redirectGetter->getRedirect($path);

        if ($redirect && $this->isRedirectAllow($redirect)) {
            $request->setDispatched(true);
            $this->response->setRedirect(
                $this->targetPathResolver->getTargetPath($redirect, $path),
                $redirect->getRedirectType()
            );

            return $this->actionFactory->create(\Magento\Framework\App\Action\Redirect::class);
        }

        return false;
    }

    /**
     * @param RedirectInterface $redirect
     * @return bool
     */
    protected function isRedirectAllow(RedirectInterface $redirect): bool
    {
        return true;
    }
}
