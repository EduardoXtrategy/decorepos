<?php

namespace Sensei\SortingPro\Plugin\Swatches\Model;

class SwatchAttributesProvider
{
    /**
     * @var \Sensei\SortingPro\Helper\Data
     */
    protected $sp2Helper;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    /**
     * @var array
     */
    protected $activeFilters;

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;


    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    public function __construct(
        \Sensei\SortingPro\Helper\Data $sp2Helper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->sp2Helper = $sp2Helper;
        $this->layerResolver = $layerResolver;
        $layer = $this->layerResolver->get();
        $this->activeFilters = $layer->getState()->getFilters();
        $this->eavConfig = $eavConfig;
        $this->resource = $resource;
    }

    public function afterProvide($subject, $result, \Magento\Catalog\Model\Product $product)
    {
        /* DecoWraps Customization - Forcing Image Preview for some attributes non-configurable or non-super attributes - Start */
        $forcedAttributes = $this->sp2Helper->getScopeValue("image_preview/forced_attributes", $this->sp2Helper->getCurrentStoreId());
        if (!trim($forcedAttributes)) {
            return $result;
        }
        $forcedAttributes = explode(",", $forcedAttributes);
        $forcedAttributes = array_map('trim', $forcedAttributes);
        if (count($forcedAttributes)) {
            $swatchAttributes = $result;
            foreach($this->activeFilters as $f) {
                $activeFilterCode = $f->getFilter()->getRequestVar();
                $activeFilterValue = $f->getValueString();
                if (!in_array($activeFilterCode, $forcedAttributes)) {
                    continue;
                }
                $attr = $this->eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $activeFilterCode);
                if ($attr && $attr->getId()) {
                    $swatchAttributes[$attr->getId()] = $attr;
                }
            }
            $result = $swatchAttributes;
        }
        /* DecoWraps Customization - Forcing Image Preview for some attributes non-configurable or non-super attributes - End */

        return $result;
    }
}
