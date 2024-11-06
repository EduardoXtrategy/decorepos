<?php

namespace Uzer\AdvancedFilter\Block\Navigation;

use Amasty\Shopby\Helper\Category;
use Amasty\Shopby\Helper\Data as ShopbyHelper;
use Amasty\Shopby\Helper\FilterSetting;
use Amasty\Shopby\Helper\UrlBuilder;

use Amasty\Shopby\Model\Source\DisplayMode;
use Amasty\ShopbyBase\Api\Data\FilterSettingInterface;
use Amasty\ShopbyBase\Helper\Data as Basehelper;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CollectionFactoryAlias;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Uzer\AdvancedFilter\Model\ProductTypeSizes;
use Uzer\AdvancedFilter\Model\ResourceModel\ProductTypeSizes\CollectionFactory;
use Uzer\AdvancedFilter\Model\SizeFilter;
use Uzer\AdvancedFilter\ViewModel\Catalog\GroupFilter;
use Uzer\AdvancedFilter\ViewModel\Catalog\GroupFilterFactory;

class FilterRenderer extends \Amasty\Shopby\Block\Navigation\FilterRenderer
{

    const KEY_DATA = 'option_sizes';
    protected static $productTypes = [];
    const CUSTOM_KEY = 'custom_position';

    protected CollectionFactory $collectionFactory;
    protected CollectionFactoryAlias $collectionFactoryCategory;
    protected GroupFilterFactory $groupFilter;

    public function __construct(
        Context                $context,
        FilterSetting          $settingHelper,
        UrlBuilder             $urlBuilder,
        ShopbyHelper           $helper,
        Category               $categoryHelper,
        Resolver               $resolver,
        Basehelper             $baseHelper,
        CollectionFactory      $collectionFactory,
        CollectionFactoryAlias $collectionFactoryCategory,
        GroupFilterFactory     $groupFilter,
        array                  $data = []
    )
    {
        parent::__construct(
            $context,
            $settingHelper,
            $urlBuilder,
            $helper,
            $categoryHelper,
            $resolver,
            $baseHelper,
            $data
        );
        $this->collectionFactory = $collectionFactory;
        $this->collectionFactoryCategory = $collectionFactoryCategory;
        $this->groupFilter = $groupFilter;
    }


    /**
     * @param FilterSettingInterface $filterSetting
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getTemplateByFilterSetting(FilterSettingInterface $filterSetting)
    {
        $productTypeDecowraps = strtolower($this->filter->getRequestVar());
        if ($productTypeDecowraps == 'product_type_decowraps') {
            self::$productTypes = $this->filter->getItems();
        }
        if ($this->filter->getRequestVar() == 'am_is_new' || $this->filter->getRequestVar() == 'discount') {
            $this->getFilterSetting()->setIsMultiselect(true);
            $this->getFilterSetting()->setShowProductQuantities(false);
            return 'layer/filter/new.phtml';
        }
        if (count(self::$productTypes) > 0 && strtolower($this->filter->getRequestVar()) == 'size') {
            $this->setItemsToSizeFilter(self::$productTypes);
            return 'layer/filter/size.phtml';
        } else if (count(self::$productTypes) <= 0 && strtolower($this->filter->getRequestVar()) == 'size') {
            return 'layer/filter/empty_size.phtml';
        } else if (strtolower($this->filter->getRequestVar()) == 'stock') {
            return 'layer/filter/stock.phtml';
        }
        switch ($filterSetting->getDisplayMode()) {
            case DisplayMode::MODE_SLIDER:
                $template = "Amasty_Shopby::layer/filter/slider.phtml";
                break;
            case DisplayMode::MODE_DROPDOWN:
                $template = $this->getMultiselectFilter($filterSetting);
                break;
            case DisplayMode::MODE_FROM_TO_ONLY:
                $template = "Amasty_Shopby::layer/widget/fromto.phtml";
                break;
            default:
                $template = "Amasty_Shopby::layer/filter/default.phtml";
                break;
        }
        return $template;
    }

    /**
     * @param \Amasty\Shopby\Model\Layer\Filter\Item[] $productTypes
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setItemsToSizeFilter(array $productTypes)
    {
        /** @var \Amasty\Shopby\Model\Layer\Filter\Item[] $sizes */
        $sizes = $this->filter->getItems();
        /** @var SizeFilter[] $newSizes */
        $newSizes = array();
        foreach ($sizes as $key => $size) {
            $size->setData(self::CUSTOM_KEY, $key);
            $sizeItem = new SizeFilter();
            $sizeItem->setItem($size);
            $sizeItem->setPosition($key);
            $newSizes[$size->getValueString()] = $sizeItem;
        }
        $checked = false;
        foreach ($productTypes as $item) {
            $checked = $this->checkedFilter($item) > 0;
            if ($checked) {
                break;
            }
        }
        $options = array();
        //Validate if has applied filters of product type decowraps
        if ($checked) {//If has applied, show only product type applieds
            foreach ($productTypes as $item) {
                if ($this->checkedFilter($item)) {
                    $result = $this->addItemToFilter($item, $newSizes);
                    if ($result) {
                        $options[] = $result;
                    }
                }
            }
        } else {//If not applied filters show all options
            foreach ($productTypes as $item) {
                $result = $this->addItemToFilter($item, $newSizes);
                if ($result) {
                    $options[] = $result;
                }
            }
        }
        $this->setData(self::KEY_DATA, $options);
    }

    /**
     * @param Item $item
     * @param SizeFilter[] $newSizes
     * @return GroupFilter|null
     */
    protected function addItemToFilter(Item $item, array $newSizes): ?GroupFilter
    {
        $collection = $this->collectionFactory->create();
        $groupFilter = $this->groupFilter->create();
        $groupFilter->setCode($item->getValueString());
        $groupFilter->setLabel($item->getLabel());
        /** @var ProductTypeSizes[] $sizeItems */
        foreach ($newSizes as $newSize) {
            $total = $this->collectionFactory->create()
                ->addFieldToFilter('product_type_id', array('eq' => $item->getValueString()))
                ->addFieldToFilter('size_id', array('eq' => $newSize->getItem()->getValueString()))
                ->addFieldToFilter('status', array('eq' => true))
                ->getSize();
            if ($total > 0) {
                $groupFilter->addItem($newSize->getItem(), $newSize->getPosition());
            }
        }
        return count($groupFilter->getItems()) > 0 ? $groupFilter : null;
    }
}

