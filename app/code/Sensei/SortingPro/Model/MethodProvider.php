<?php

namespace Sensei\SortingPro\Model;

use Sensei\SortingPro\Api\MethodInterface;
use Sensei\SortingPro\Api\IndexMethodWrapperInterface;
use Sensei\SortingPro\Helper\Data as Helper;
use Magento\Framework\Exception\LocalizedException;

class MethodProvider
{

    private $scopeConfig;
    private $indexedMethods = [];
    private $methods = [];
    private $helper;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Helper $helper,
        $indexedMethods = [],
        $methods = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
        $this->initMethods($indexedMethods, $methods);
    }

    private function initMethods($indexedMethods = [], $methods = [])
    {
        foreach ($indexedMethods as $methodWrapper) {
            $this->indexedMethods[$methodWrapper->getSource()->getMethodCode()] = $methodWrapper;
        }
        foreach ($methods as $methodObject) {
            if (!$methodObject instanceof MethodInterface) {
                if (is_object($methodObject)) {
                    throw new LocalizedException(
                        __('Method object ' . get_class($methodObject) .
                            ' must be implemented by Sensei\SortingPro\Api\MethodInterface')
                    );
                } else {
                    throw new LocalizedException(__('$methodObject is not object'));
                }
            }
            $this->methods[$methodObject->getMethodCode()] = $methodObject;
        }
    }

    public function getMethodByCode($code)
    {
        if (isset($this->methods[$code]) && !$this->helper->isMethodDisabled($code)) {
            return $this->methods[$code];
        }

        return null;
    }


    public function getIndexedMethods()
    {
        return $this->indexedMethods;
    }


    public function getMethods()
    {
        return $this->methods;
    }
}
