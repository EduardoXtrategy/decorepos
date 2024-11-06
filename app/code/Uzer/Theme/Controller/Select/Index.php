<?php

namespace Uzer\Theme\Controller\Select;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Store\Api\StoreCookieManagerInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Theme\Helper\Data;
use Uzer\Theme\Model\Cookie;

class Index implements HttpPostActionInterface
{

    private array $countryOptions = [
        'co' => 4,
        'ec' => 6,
        'eu' => 3,
        'us' => 2,
        'ca' => 2
    ];

    private array $storeViewDefault = [
        'us' => 'us_en',
        'ca' => 'us_en',
        'co' => 'lat_en',
        'ec' => 'ec_en',
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
     * @param StoreManagerInterface $storeManager
     * @param Cookie $cookie
     */
    public function __construct(
        RequestInterface            $request,
        StoreCookieManagerInterface $_storeCookieManager,
        StoreRepositoryInterface    $_storeRepository,
        RedirectFactory             $resultRedirectFactory,
        StoreManagerInterface       $storeManager,
        Cookie                      $cookie,
        Data                        $helperData
    )
    {
        $this->request = $request;
        $this->_storeCookieManager = $_storeCookieManager;
        $this->_storeRepository = $_storeRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->storeManager = $storeManager;
        $this->cookie = $cookie;
        $this->helperData = $helperData;
    }


    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Redirect|ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Store\Model\StoreIsInactiveException
     * @throws \Exception
     */
    public function execute()
    {
        $result = $this->resultRedirectFactory->create();
        $region = $this->request->getParam('location_region');
        if (isset($this->countryOptions[$region])) {
            $storeViewCode = $this->storeViewDefault[$region];
            $store = $this->_storeRepository->getActiveStoreByCode($storeViewCode);
            $this->cookie->setCustomCookie($store->getId());
            $result->setUrl($store->getUrl());
            return $result;

        }
        $result->setRefererUrl();
        return $result;
    }
}
