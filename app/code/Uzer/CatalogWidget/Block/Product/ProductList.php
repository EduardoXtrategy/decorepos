<?php

namespace Uzer\CatalogWidget\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogWidget\Model\Rule;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Rule\Model\Condition\Sql\Builder as SqlBuilder;
use Magento\Widget\Helper\Conditions;

class ProductList extends \Magento\CatalogWidget\Block\Product\ProductsList
{

    private static int $counter = 0;
    protected $_template = 'zer_CatalogWidget::product/widget/content/grid.phtml';

    public function __construct(
        Context                     $context,
        CollectionFactory           $productCollectionFactory,
        Visibility                  $catalogProductVisibility,
        HttpContext                 $httpContext,
        SqlBuilder                  $sqlBuilder,
        Rule                        $rule,
        Conditions                  $conditionsHelper,
        array                       $data = [],
        Json                        $json = null,
        LayoutFactory               $layoutFactory = null,
        EncoderInterface            $urlEncoder = null,
        CategoryRepositoryInterface $categoryRepository = null
    )
    {
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $sqlBuilder,
            $rule,
            $conditionsHelper,
            $data,
            $json,
            $layoutFactory,
            $urlEncoder,
            $categoryRepository
        );
    }

    public function increase()
    {
        self::$counter++;
    }

    /**
     * @return int
     */
    public static function getCounter(): int
    {
        return self::$counter;
    }


}
