<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Plugin\Elasticsearch\Model\ResourceModel\Fulltext\Collection\SearchResultApplier;

use Sensei\SortingPro\Model\Elasticsearch\ApplierFlag;
use Magento\Elasticsearch\Model\ResourceModel\Fulltext\Collection\SearchResultApplier;

class SetApplierFlag
{

    private $applierFlag;

    public function __construct(ApplierFlag $applierFlag)
    {
        $this->applierFlag = $applierFlag;
    }

    public function aroundApply(SearchResultApplier $subject, callable $proceed): void
    {
        $this->applierFlag->enable();
        $proceed();
        $this->applierFlag->disable();
    }
}
