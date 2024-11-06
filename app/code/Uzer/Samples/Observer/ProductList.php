<?php

namespace Uzer\Samples\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Samples\Logger\Logger;
use Uzer\Samples\Model\Session\FilterSession;


class ProductList implements ObserverInterface
{

    protected Logger $logger;
    protected UrlInterface $_urlInterface;
    protected StoreManagerInterface $storeManager;
    protected FilterSession $filterSession;

    /**
     * @param Logger $logger
     * @param UrlInterface $_urlInterface
     * @param StoreManagerInterface $storeManager
     * @param FilterSession $filterSession
     */
    public function __construct(
        Logger                $logger,
        UrlInterface          $_urlInterface,
        StoreManagerInterface $storeManager,
        FilterSession         $filterSession
    )
    {
        $this->logger = $logger;
        $this->_urlInterface = $_urlInterface;
        $this->storeManager = $storeManager;
        $this->filterSession = $filterSession;
    }


    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute(Observer $observer)
    {
        if (!$this->filterSession->isSessionExists()) {
            $this->filterSession->start();
        }
        $currentUrl = $this->_urlInterface->getCurrentUrl();
        $this->filterSession->saveUrlFilter($this->storeManager->getStore()->getCode(), $currentUrl);
    }
}
