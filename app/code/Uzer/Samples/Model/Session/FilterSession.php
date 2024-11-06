<?php

namespace Uzer\Samples\Model\Session;

use Magento\Framework\App\Request\Http;
use Magento\Framework\App\State;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\SessionStartChecker;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\ValidatorInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class FilterSession extends \Magento\Framework\Session\SessionManager
{

    const SESSION_NAME = 'applied_filters';

    protected Storage $customStorage;

    public function __construct(
        Http                   $request,
        SidResolverInterface   $sidResolver,
        ConfigInterface        $sessionConfig,
        SaveHandlerInterface   $saveHandler,
        ValidatorInterface     $validator,
        Storage                $storage,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory  $cookieMetadataFactory,
        State                  $appState, SessionStartChecker $sessionStartChecker = null
    )
    {
        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState,
            $sessionStartChecker
        );
        $this->customStorage = $storage;
    }

    public function saveUrlFilter(string $storeCode, string $url)
    {

        $this->customStorage->setData($this->getSessionName($storeCode), $url);
    }

    public function getUrlFilter(string $storeCode)
    {
        return $this->customStorage->getData($this->getSessionName($storeCode));
    }


    protected function getSessionName(string $storeCode): string
    {
        return sprintf('%s_%s', $storeCode, self::SESSION_NAME);
    }


}
