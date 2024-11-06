<?php
namespace Uzer\Theme\Plugin;

use Amasty\Base\Model\MagentoVersion;
use Amasty\Base\Model\Serializer;
use Amasty\Geoip\Model\Geolocation;
use Amasty\GeoipRedirect\Helper\Data;
use Amasty\GeoipRedirect\Model\RedirectUrl\UrlProcessor;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Router\Base;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\StoreCookieManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Uzer\Theme\Model\Cookie;

class Action extends \Amasty\GeoipRedirect\Plugin\Action
{

    protected Cookie $cookie;

    public function __construct(RemoteAddress $remoteAddress, ScopeConfigInterface $scopeConfig, Data $geoipHelper, UrlInterface $urlBuilder, StoreManagerInterface $storeManager, Geolocation $geolocation, Session $customerSession, StoreCookieManagerInterface $storeCookieManager, RedirectFactory $resultRedirectFactory, Http $response, SessionManagerInterface $sessionManager, ResolverInterface $resolver, Serializer $serializer, UrlFinderInterface $urlFinder, Base $baseRouter, MagentoVersion $magentoVersion, UrlProcessor $urlProcessor, Cookie $cookie)
    {
        parent::__construct($remoteAddress, $scopeConfig, $geoipHelper, $urlBuilder, $storeManager, $geolocation, $customerSession, $storeCookieManager, $resultRedirectFactory, $response, $sessionManager, $resolver, $serializer, $urlFinder, $baseRouter, $magentoVersion, $urlProcessor);
        $this->cookie = $cookie;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Amasty\GeoipRedirect\Plugin\Action::aroundDispatch()
     */
    public function aroundDispatch(FrontControllerInterface $subject, \Closure $proceed, RequestInterface $request)
    {
        if ($this->cookie->getCustomCookie()) {            
            return $proceed($request);
        }
        return parent::aroundDispatch($subject, $proceed, $request);
    }
}
