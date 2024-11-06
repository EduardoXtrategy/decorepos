<?php

namespace Sensei\SortingPro\Model\Indexer;

use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\App\Cache\TypeListInterface as CacheTypeListInterface;
use Magento\Framework\Indexer\CacheContext;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Registry;

class AbstractIndexer implements IndexerActionInterface, MviewActionInterface
{

    private $indexBuilder;

    private $helper;

    private $cache;

    private $cacheContext;

    private $eventManager;

    private $registry;

    public function __construct(
        \Sensei\SortingPro\Api\IndexedMethodInterface $indexBuilder,
        \Sensei\SortingPro\Helper\Data $helper,
        CacheTypeListInterface $cache,
        CacheContext $cacheContext,
        ManagerInterface $eventManager,
        Registry $registry
    ) {
        $this->indexBuilder = $indexBuilder;
        $this->helper = $helper;
        $this->cache = $cache;
        $this->cacheContext = $cacheContext;
        $this->eventManager = $eventManager;
        $this->registry = $registry;
    }

    public function execute($ids)
    {
        $this->executeList($ids);
    }

    public function executeFull()
    {
        // do full reindex if method is not disabled
        if (!$this->helper->isMethodDisabled($this->indexBuilder->getMethodCode())
            && !$this->registry->registry('reindex_' . $this->indexBuilder->getMethodCode())
        ) {
            $this->indexBuilder->reindex();
            $this->cacheContext->registerTags(
                ['sorted_by_' . $this->indexBuilder->getMethodCode()]
            );
            $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $this->cacheContext]);
            $this->registry->register('reindex_' . $this->indexBuilder->getMethodCode(), true);
        }
    }

    public function executeList(array $ids)
    {
        if (!$ids) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Could not rebuild index for empty products array')
            );
        }
        $this->doExecuteList($ids);
    }

    protected function doExecuteList($ids)
    {
        $this->executeFull();
    }

    private function doExecuteRow($id)
    {
        $this->executeFull();
    }

    public function executeRow($id)
    {
        if (!$id) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('We can\'t rebuild the index for an undefined product.')
            );
        }
        $this->doExecuteRow($id);
    }
}
