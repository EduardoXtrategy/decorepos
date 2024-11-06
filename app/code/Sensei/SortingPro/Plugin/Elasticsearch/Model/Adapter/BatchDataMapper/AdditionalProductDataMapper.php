<?php

namespace Sensei\SortingPro\Plugin\Elasticsearch\Model\Adapter\BatchDataMapper;

use Sensei\SortingPro\Helper\Data;
use Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapperInterface;
use Sensei\SortingPro\Model\Elasticsearch\SkuRegistry;
use Magento\Elasticsearch\Model\Adapter\BatchDataMapper\ProductDataMapper;

class AdditionalProductDataMapper
{

    private $skuRegistry;

    private $helper;

    private $dataMappers = [];

    public function __construct(
        SkuRegistry $skuRegistry,
        Data $helper,
        array $dataMappers = []
    ) {
        $this->skuRegistry = $skuRegistry;
        $this->helper = $helper;
        $this->dataMappers = $dataMappers;
    }

    /**
     * Prepare index data for using in search engine metadata.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param ProductDataMapper $subject
     * @param callable $proceed
     * @param array $documentData
     * @param $storeId
     * @param array $context
     * @return array
     */
    public function aroundMap(
        $subject,
        callable $proceed,
        array $documentData,
        $storeId,
        $context = []
    ) {
        $documentData = $proceed($documentData, $storeId, $context);
        if ($this->helper->getOutOfStockLast($storeId)) {
            /** load sku relations needed in @see \Sensei\SortingPro\Model\Elasticsearch\Adapter\DataMapper\Stock */
            $this->skuRegistry->save(array_keys($documentData));
        }
        foreach ($documentData as $productId => $document) {
            $context['document'] = $document;
            foreach ($this->dataMappers as $mapper) {
                if ($mapper instanceof DataMapperInterface && $mapper->isAllowed($storeId)) {
                    //@codingStandardsIgnoreLine
                    $document = array_merge($document, $mapper->map($productId, $document, $storeId, $context));
                }
            }
            $documentData[$productId] = $document;
        }
        $this->skuRegistry->clear();

        return $documentData;
    }
}
