<?php

namespace Sensei\SortingPro\Model;

class Logger
{
    const DEBUG_CONFIG_PATH = 'scsorting/general/debug';
    const DEBUG_REQUEST_VAR = 'scdebug';
    
    private $logger;

    private $scopeConfig;

    private $request;

    public function __construct(
        \Amasty\Base\Debug\VarDump $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
    }

    public function logCollectionQuery($collection)
    {
        if ($this->scopeConfig->isSetFlag(self::DEBUG_CONFIG_PATH)
            && $this->request->getParam(self::DEBUG_REQUEST_VAR, false)
        ) {
            $this->logger->amastyEcho($collection->getSelect()->__toString());
        }
        return $this;
    }
}
