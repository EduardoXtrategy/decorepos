<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

use Sensei\SortingPro\Api\MethodInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

abstract class AbstractMethod extends AbstractDb implements MethodInterface
{

    const ENABLED = true;

    protected $scopeConfig;

    protected $storeManager;

    protected $request;

    protected $methodCode;

    protected $methodName;

    protected $helper;

    protected $logger;

    protected $date;

    protected $indexConnection = null;

    private $data;

    private $escaper;

    public function __construct(
        Context $context,
        \Magento\Framework\Escaper $escaper,
        $connectionName = null,
        $methodCode = '',
        $methodName = '',
        AbstractDb $indexResource = null,
        $data = []
    ) {
        $this->scopeConfig      = $context->getScopeConfig();
        $this->request          = $context->getRequest();
        $this->storeManager     = $context->getStoreManager();
        $this->helper           = $context->getHelper();
        $this->logger           = $context->getLogger();
        $this->date             = $context->getDate();
        $this->methodCode       = $methodCode;
        $this->methodName       = $methodName;
        if ($indexResource) {
            $this->indexConnection = $indexResource->getConnection();
        }
        $this->data = $data;
        parent::__construct($context, $connectionName);
        $this->escaper = $escaper;
    }

    //@codingStandardsIgnoreStart
    protected function _construct()
    {
        // dummy
    }
    //@codingStandardsIgnoreEnd

    abstract public function apply($collection, $direction);

    protected function isMethodAlreadyApplied($collection)
    {
        return (bool) $collection->getFlag($this->getFlagName());
    }

    protected function markApplied($collection)
    {
        $collection->setFlag($this->getFlagName(), true);
    }

    protected function getFlagName()
    {
        return  'sorted_by_' . $this->getMethodCode();
    }

    abstract public function getIndexedValues($storeId);

    public function isActive()
    {
        return !$this->helper->isMethodDisabled($this->getMethodCode());
    }

    public function getMethodCode()
    {
        if (empty($this->methodCode)) {
            $this->logger->warning('Undefined Sensei Commerce sorting method code, add method code to di.xml');
        }
        return $this->methodCode;
    }

    public function getMethodName()
    {
        if (empty($this->methodCode)) {
            $this->logger->warning('Undefined Sensei Commerce sorting method code, add method code to di.xml');
        }
        return $this->methodName;
    }

    public function getMethodLabel($store = null)
    {
        $label = $this->helper->getScopeValue($this->getMethodCode() . '/label', $store);
        if (!$label) {
            $label = __($this->getMethodName());
        }

        return $this->escaper->escapeHtml($label);
    }

    protected function getAdditionalData($key)
    {
        $result = null;
        if (isset($this->data[$key])) {
            $result = $this->data[$key];
        }

        return $result;
    }
}
