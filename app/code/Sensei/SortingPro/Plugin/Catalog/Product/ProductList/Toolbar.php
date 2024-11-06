<?php

namespace Sensei\SortingPro\Plugin\Catalog\Product\ProductList;

use Sensei\SortingPro\Helper\Data;
use Magento\Framework\Registry;

class Toolbar
{
    const ALWAYS_DESC = [
        'price_desc'
    ];

    const ALWAYS_ASC = [
        'price_asc'
    ];

    const RELEVANCE_DIRECTION = 'relevance';

    private $helper;

    private $methodProvider;

    private $toolbarModel;

    private $imageMethod;

    private $stockMethod;

    private $registry;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    /**
     * @var array
     */
    protected $activeFilters;

    /**
     * @var ToolbarMemorizer
     */
    protected $toolbarMemorizer;

    public function __construct(
        Data $helper,
        \Sensei\SortingPro\Model\MethodProvider $methodProvider,
        \Magento\Catalog\Model\Product\ProductList\Toolbar $toolbarModel,
        \Sensei\SortingPro\Model\ResourceModel\Method\Image $imageMethod,
        \Sensei\SortingPro\Model\ResourceModel\Method\Instock $stockMethod,
        Registry $registry,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer $toolbarMemorizer = null
    ) {
        $this->helper = $helper;
        $this->methodProvider = $methodProvider;
        $this->toolbarModel = $toolbarModel;
        $this->imageMethod = $imageMethod;
        $this->stockMethod = $stockMethod;
        $this->registry = $registry;
        $this->layerResolver = $layerResolver;
        $layer = $this->layerResolver->get();
        $this->activeFilters = $layer->getState()->getFilters();
        $this->toolbarMemorizer = $toolbarMemorizer ?: \Magento\Framework\App\ObjectManager::getInstance()->get(
            \Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer::class
        );
    }

    public function afterGetCurrentDirection($subject, $dir)
    {
        $defaultDir = $this->isDescDir($subject->getCurrentOrder()) ? 'desc' : 'asc';
        $subject->setDefaultDirection($defaultDir);

        if (!$this->toolbarModel->getDirection()
            || $this->shouldSetDirection($subject->getCurrentOrder())
        ) {
            $dir = $defaultDir;
        }

        $activeFilters = $this->getActiveCounterFilters(true);
        $hasActiveFilters = false;
        foreach ($activeFilters as $afk => $afv) {
            if ($afk == "holiday") {
                $hasActiveFilters = true;
            }
        }
        if (!$hasActiveFilters) {
            foreach ($activeFilters as $afk => $afv) {
                if ($afk == "season") {
                    $hasActiveFilters = true;
                }
            }
        }
        $defaultSortings = $this->helper->getCategorySorting();
        $userDir = strtolower($this->toolbarMemorizer->getDirection());
        $userOrder = $this->toolbarMemorizer->getOrder();
        if (!$hasActiveFilters && !($userDir || $userOrder) && !$this->registry->registry(Data::SEARCH_SORTING)) {
            $dir = "desc";
        }

        return $dir;
    }

    private function isDescDir($order)
    {
        $attributeCodes = $this->helper->getScopeValue('general/desc_attributes');
        $shouldBeDesc = array_merge(self::ALWAYS_DESC, [self::RELEVANCE_DIRECTION]);

        if ($attributeCodes) {
            $shouldBeDesc = array_merge($shouldBeDesc, explode(',', $attributeCodes));
        }

        return in_array($order, $shouldBeDesc);
    }

    private function shouldSetDirection($order)
    {
        return in_array($order, self::ALWAYS_DESC) || in_array($order, self::ALWAYS_ASC);
    }

    public function beforeSetCollection($subject, $collection)
    {
        if ($collection instanceof \Magento\Catalog\Model\ResourceModel\Product\Collection) {
            // no image sorting will be the first or the second (after stock). LIFO queue
            $this->imageMethod->apply($collection);
            // in stock sorting will be first, as the method always moves it's paremater first. LIFO queue
            $this->stockMethod->apply($collection);
        }

        return [$collection];
    }

    public function afterSetCollection($subject, $result)
    {
        $this->applyOrdersFromConfig($subject->getCollection());

        return $result;
    }

    private function applyOrdersFromConfig($collection)
    {
        if ($this->registry->registry(Data::SEARCH_SORTING)) {
            $defaultSortings = $this->helper->getSearchSorting();
        } else {
            $defaultSortings = $this->helper->getCategorySorting();
        }
        // first sorting must be setting by magento as default sorting
        $firstOrder = array_shift($defaultSortings);

        $activeFilters = $this->getActiveCounterFilters();

        if (isset($activeFilters["holiday"]) && $firstOrder == "holidays_counter") {
            foreach ($activeFilters["holiday"] as $afk => $afv) {
                if ($afk == 0) continue;
                $collection->setOrder("holidays_counter" . "_" . $afv, "asc");
            }
        }
        if (isset($activeFilters["season"]) && $firstOrder == "seasons_counter") {
            foreach ($activeFilters["season"] as $afk => $afv) {
                if ($afk == 0) continue;
                $collection->setOrder("seasons_counter" . "_" . $afv, "asc");
            }
        }

        foreach ($defaultSortings as $defaultSorting) {
            $dir = $this->isDescDir($defaultSorting) ? 'desc' : 'asc';
            if ($defaultSorting == "holidays_counter" && isset($activeFilters["holiday"]) && $firstOrder != "holiday") {
                foreach ($activeFilters["holiday"] as $afk => $afv) {
                    $collection->setOrder($defaultSorting . "_" . $afv, $dir);
                }
            } elseif ($defaultSorting == "seasons_counter" && isset($activeFilters["season"]) && $firstOrder != "season") {
                foreach ($activeFilters["season"] as $afk => $afv) {
                    $collection->setOrder($defaultSorting . "_" . $afv, $dir);
                }
            } else {
                $collection->setOrder($defaultSorting, $dir);
            }
        }
    }

    protected function getActiveCounterFilters($onlyCounters = false)
    {
        if ($this->registry->registry($this->helper::SEARCH_SORTING)) {
            return [];
        }
        $defaultSortings = $this->helper->getCategorySorting();

        $hasActiveFilters = false;
        $filters = [];
        $subQueryAlias =  "holiday_order";
        foreach($this->activeFilters as $f) {
            $activeFilterCode = $f->getFilter()->getRequestVar();
            $activeFilterValue = $f->getValueString();
            if ($onlyCounters) {
                if ($activeFilterCode == "holiday" && $activeFilterValue && in_array("holidays_counter", $defaultSortings)) {
                    $filters[$activeFilterCode][] = $activeFilterValue;
                }
                if ($activeFilterCode == "season" && $activeFilterValue && in_array("seasons_counter", $defaultSortings)) {
                    $filters[$activeFilterCode][] = $activeFilterValue;
                }
            } else {
                $filters[$activeFilterCode][] = $activeFilterValue;
            }
        }

        return $filters;
    }

    /**
     * Get grid products sort order field
     *
     * @return string
     */
    public function afterGetCurrentOrder($subject, $order)
    {
        $userOrder = $this->toolbarMemorizer->getOrder();
        if ($userOrder) {
            return $order;
        }
        $counterOrder = $subject->getData('_current_grid_order_counter');
        if ($counterOrder) {
            return $counterOrder;
        }

        $activeFilters = $this->getActiveCounterFilters(true);

        $hasActiveFilters = false;
        foreach ($activeFilters as $afk => $afv) {
            if ($afk == "holiday") {
                $order = "holidays_counter_" .$afv[0];
                $subject->setData('_current_grid_order', $order);
                $subject->setData('_current_grid_order_counter', $order);
                $hasActiveFilters = true;
            }
        }

        if (!$hasActiveFilters) {
            foreach ($activeFilters as $afk => $afv) {
                if ($afk == "season") {
                    $order = "seasons_counter_" .$afv[0];
                    $subject->setData('_current_grid_order', $order);
                    $subject->setData('_current_grid_order_counter', $order);
                    $hasActiveFilters = true;
                }
            }
        }

        $defaultSortings = $this->helper->getCategorySorting();
        if (!$hasActiveFilters && !$this->registry->registry(Data::SEARCH_SORTING)) {
            $order = "news_from_date";
            $subject->setData('_current_grid_order', $order);
            $subject->setData('_current_grid_order_counter', $order);
        }

        return $order;
    }
}
