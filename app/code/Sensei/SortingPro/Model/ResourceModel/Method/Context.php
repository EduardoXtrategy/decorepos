<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\ResourceModel\Db\ObjectRelationProcessor;
use Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface;
use Magento\Framework\ObjectManager\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class Context extends \Magento\Framework\Model\ResourceModel\Db\Context implements ContextInterface
{

    private $scopeConfig;

    private $storeManager;

    private $request;

    private $helper;

    private $logger;

    private $date;

    /**
     * Context constructor.
     *
     * @param \Magento\Framework\App\ResourceConnection   $resource
     * @param TransactionManagerInterface                 $transactionManager
     * @param ObjectRelationProcessor                     $objectRelationProcessor
     * @param ScopeConfigInterface                        $scopeConfig
     * @param RequestInterface                            $request
     * @param StoreManagerInterface                       $storeManager
     * @param \Sensei\Sorting\Helper\Data                 $helper
     * @param \Psr\Log\LoggerInterface                    $logger
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        TransactionManagerInterface $transactionManager,
        ObjectRelationProcessor $objectRelationProcessor,
        ScopeConfigInterface $scopeConfig,
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        \Sensei\SortingPro\Helper\Data $helper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct($resource, $transactionManager, $objectRelationProcessor);
        $this->scopeConfig  = $scopeConfig;
        $this->request      = $request;
        $this->storeManager = $storeManager;
        $this->helper       = $helper;
        $this->logger       = $logger;
        $this->date         = $date;
    }

    public function getScopeConfig()
    {
        return $this->scopeConfig;
    }

    public function getStoreManager()
    {
        return $this->storeManager;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getHelper()
    {
        return $this->helper;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getDate()
    {
        return $this->date;
    }
}
