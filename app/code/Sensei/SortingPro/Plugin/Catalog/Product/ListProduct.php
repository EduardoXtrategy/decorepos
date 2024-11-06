<?php

namespace Sensei\SortingPro\Plugin\Catalog\Product;

use Sensei\SortingPro\Model\Logger as SenseiLogger;
use Sensei\SortingPro\Model\MethodProvider;
use Magento\Catalog\Block\Product\ListProduct as NativeList;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;

class ListProduct
{

    private $methodProvider;

    private $logger;

    public function __construct(MethodProvider $methodProvider, SenseiLogger $logger)
    {
        $this->methodProvider = $methodProvider;
        $this->logger = $logger;
    }

    /**
     * @param NativeList $subject
     * @param array $identities
     *
     * @return array
     */
    public function afterGetIdentities(NativeList $subject, $identities)
    {
        /** @var Toolbar $toolbarBlock */
        $toolbarBlock = $subject->getLayout()->getBlock('product_list_toolbar');
        if ($toolbarBlock
            && in_array(
                $toolbarBlock->getCurrentOrder(),
                array_keys($this->methodProvider->getIndexedMethods())
            )
        ) {
            $identities[] = 'sorted_by_' . $toolbarBlock->getCurrentOrder();
        }

        return $identities;
    }

    /**
     * @param NativeList $subject
     * @param AbstractCollection $result
     *
     * @return AbstractCollection
     */
    public function afterGetLoadedProductCollection(NativeList $subject, $result)
    {
        $this->logger->logCollectionQuery($result);

        return $result;
    }
}
