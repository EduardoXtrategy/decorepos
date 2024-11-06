<?php

namespace Sensei\SortingPro\Model\ResourceModel\Method;

use Sensei\SortingPro\Model\Source\Image as ImageSource;
use Magento\Sitemap\Model\ResourceModel\Catalog\Product as ProductResource;

class Image extends AbstractMethod
{

    public function getSortingColumnName()
    {
        return 'small_image';
    }

    public function apply($collection, $direction = '')
    {
        if (!$this->isMethodActive($collection) || $this->isMethodAlreadyApplied($collection)) {
            return $this;
        }

        $attribute = $this->getSortingColumnName();

        $collection->addAttributeToSelect($attribute, 'left');
        $collection->getSelect()->order($this->getSortExpression($attribute));

        $orders = $collection->getSelect()->getPart(\Zend_Db_Select::ORDER);
        // move from the last to the the first position
        array_unshift($orders, array_pop($orders));
        $collection->getSelect()->setPart(\Zend_Db_Select::ORDER, $orders);

        $this->markApplied($collection);

        return $this;
    }

    private function isMethodActive($collection)
    {
        $show = $this->helper->getNonImageLast();

        if (!$show || ($show == ImageSource::SHOW_LAST_FOR_CATALOG && $this->isSearchModule())) {
            return false;
        }

        return true;
    }

    private function isSearchModule()
    {
        return in_array(
            $this->request->getModuleName(),
            ['sqli_singlesearchresult', 'catalogsearch']
        );
    }

    private function getSortExpression($imageColumn)
    {
        $connection = $this->getConnection();
        $noSelection = $connection->quote(ProductResource::NOT_SELECTED_IMAGE);
        /** IFNULL(e.small_image, 'no_selection') */
        $ifNull = $connection->getIfNullSql($imageColumn, $noSelection);
        /** IFNULL(e.small_image, 'no_selection')='no_selection' */
        $ifNull .= '=' . $noSelection;

        /** IF(IFNULL(e.small_image, 'no_selection')='no_selection', 1, 0) */
        return $connection->getCheckSql($ifNull, 1, 0);
    }

    public function getIndexedValues($storeId)
    {
        return [];
    }
}
