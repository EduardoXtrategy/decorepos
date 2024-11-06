<?php

namespace Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper;

use Sensei\SortingPro\Helper\Data;
use Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapperInterface;

class Image implements DataMapperInterface
{

    private $data;

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function map($entityId, array $entityIndexData, $storeId, $context = [])
    {
        $value = isset($context['document']['small_image'])
            ? (int) ($context['document']['small_image'] !== 'no_selection')
            : 0;

        return ['non_images_last' => $value];
    }

    public function isAllowed($storeId)
    {
        return $this->data->getNonImageLast($storeId);
    }
}
