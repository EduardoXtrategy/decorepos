<?php

namespace Uzer\Theme\Controller\Select;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Store\Api\StoreCookieManagerInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\StoreIsInactiveException;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Theme\Helper\Data;
use Uzer\Theme\Model\Cookie;

class Redirect implements HttpGetActionInterface
{

    private array $countryOptions = [
        'co' => 4,
        'ec' => 4,
        'eu' => 3,
        'us' => 2,
        'ca' => 2
    ];

    private array $storeViewDefault = [
        'us' => 'us_en',
        'ca' => 'us_en',
        'co' => 'lat_en',
        'ec' => 'lat_en',
        'eu' => 'eu_en'
    ];

    protected RequestInterface $request;
    protected StoreCookieManagerInterface $_storeCookieManager;
    protected StoreRepositoryInterface $_storeRepository;
    protected RedirectFactory $resultRedirectFactory;
    protected Cookie $cookie;
    protected StoreManagerInterface $storeManager;
    protected Data $helperData;

    /**
     * @param RequestInterface $request
     * @param StoreCookieManagerInterface $_storeCookieManager
     * @param StoreRepositoryInterface $_storeRepository
     * @param RedirectFactory $resultRedirectFactory
     * @param Cookie $cookie
     * @param StoreManagerInterface $storeManager
     * @param Data $helperData
     */
    public function __construct(
        RequestInterface            $request,
        StoreCookieManagerInterface $_storeCookieManager,
        StoreRepositoryInterface    $_storeRepository,
        RedirectFactory             $resultRedirectFactory,
        Cookie                      $cookie,
        StoreManagerInterface       $storeManager,
        Data                        $helperData
    )
    {
        $this->request = $request;
        $this->_storeCookieManager = $_storeCookieManager;
        $this->_storeRepository = $_storeRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->cookie = $cookie;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
    }


    /**
     * @throws NoSuchEntityException
     * @throws FailureToSendException
     * @throws CookieSizeLimitReachedException
     * @throws StoreIsInactiveException
     * @throws InputException
     */
    public function execute()
    {
        $region = $this->request->getParam('region');
        $result = $this->resultRedirectFactory->create();
        if (isset($this->countryOptions[$region])) {
            $storeViewCode = $this->storeViewDefault[$region];
            $store = $this->_storeRepository->getActiveStoreByCode($storeViewCode);
            $this->_storeCookieManager->setStoreCookie($store);
            $this->cookie->setCustomCookie($store->getId());
            $result->setUrl($store->getUrl());
            return $result;
        }
        $result->setRefererUrl();
        return $result;
    }
}
