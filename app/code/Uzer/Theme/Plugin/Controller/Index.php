<?php

namespace Uzer\Theme\Plugin\Controller;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Store\Api\StoreCookieManagerInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Theme\Model\Cookie;

class Index
{

    protected RequestInterface $request;
    protected StoreCookieManagerInterface $_storeCookieManager;
    protected StoreRepositoryInterface $_storeRepository;
    protected RedirectFactory $resultRedirectFactory;
    protected StoreManagerInterface $storeManager;
    protected Cookie $cookie;

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
        Cookie                      $cookie
    )
    {
        $this->request = $request;
        $this->_storeCookieManager = $_storeCookieManager;
        $this->_storeRepository = $_storeRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->cookie = $cookie;
        $this->storeManager = $storeManager;
    }


    /**
     * @param \Magento\Cms\Controller\Index\Index $subject
     * @param $result
     * @return \Magento\Framework\Controller\Result\Redirect|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Store\Model\StoreIsInactiveException
     */
    public function afterExecute(\Magento\Cms\Controller\Index\Index $subject, $result)
    {
        $currentStoreId = $this->storeManager->getStore()->getId();
        $selectedStoreId = $this->cookie->getCustomCookie();
        if (!is_null($selectedStoreId) && $currentStoreId != $selectedStoreId) {
            $selectedStore = $this->_storeRepository->getActiveStoreById($selectedStoreId);
            $resultUrl = $this->resultRedirectFactory->create();
            $resultUrl->setUrl($selectedStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK));
            return $resultUrl;
        }
        return $result;
    }

}
