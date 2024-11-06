<?php

namespace Sensei\SortingPro\Model\Indexer;

use Magento\Framework\Indexer\AbstractProcessor;

class ConfigInvalidateAbstract extends \Magento\Framework\App\Config\Value
{

    private $indexProcessor;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        AbstractProcessor $indexProcessor,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->indexProcessor = $indexProcessor;
    }

    public function afterSave()
    {
        $this->_getResource()->addCommitCallback([$this, 'processValue']);
        return parent::afterSave();
    }

    public function processValue()
    {
        if ($this->getValue() != $this->getOldValue()) {
            $this->indexProcessor->markIndexerAsInvalid();
        }
    }
}
