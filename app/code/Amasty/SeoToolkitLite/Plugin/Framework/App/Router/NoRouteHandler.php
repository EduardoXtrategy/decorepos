<?php

namespace Amasty\SeoToolkitLite\Plugin\Framework\App\Router;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Router\NoRouteHandler as NativeNoRouteHandler;
use Magento\Search\Model\QueryFactory;

class NoRouteHandler
{
    /**
     * @var \Amasty\SeoToolkitLite\Helper\Config
     */
    private $config;

    /**
     * NoRouteHandler constructor.
     * @param \Amasty\SeoToolkitLite\Helper\Config $config
     */
    public function __construct(
        \Amasty\SeoToolkitLite\Helper\Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param NativeNoRouteHandler $subject
     * @param $proceed
     * @param RequestInterface $request
     * @return bool
     */
    public function aroundProcess(
        NativeNoRouteHandler $subject,
        $proceed,
        RequestInterface $request
    ) {
        $pathInfo = $this->getPathInfo($request);
        if ($this->isRedirectEnabled($request) && $pathInfo) {
            $request->setParam(QueryFactory::QUERY_VAR_NAME, $pathInfo);
            $request->setModuleName('amasty_seotoolkitlite')->setControllerName('redirect')->setActionName('index');

            return true;
        }

        return $proceed($request);
    }

    /**
     * @param RequestInterface $request
     * @return mixed|string
     */
    private function getPathInfo(RequestInterface $request)
    {
        $pathInfo = $request->getOriginalPathInfo() ?: $request->getPathInfo();
        $pathInfo = trim($pathInfo, '/');
        $pathInfo = str_replace('/', ' ', $pathInfo);
        $pathInfo = str_replace('-', ' ', $pathInfo);
        $pathInfo = str_replace('.html', '', $pathInfo);
        $pathInfo = str_replace('.htm', '', $pathInfo);

        return $pathInfo;
    }

    /**
     * @param RequestInterface $request
     * @return bool
     */
    private function isRedirectEnabled($request)
    {
        return $this->config->getModuleConfig('general/four_zero_four_redirect')
            && !$request->isAjax()
            && !preg_match('@^.*\.[a-z0-9]+(?<!.html)(?<!.htm)$@', $request->getOriginalPathInfo());
    }
}
