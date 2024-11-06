<?php
namespace Sensei\SortingPro\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use function PHPUnit\Framework\throwException;

/**
 * observer name: update_counters_attributes
 * event names:
 *     catalog_product_save_before
 */
class UpdateCountersAttributes implements ObserverInterface
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    private $_eavConfig;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    protected $_configurable;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Magento\Eav\Model\Config $eavConfig
     */
    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_eavConfig = $eavConfig;
        $this->_configurable = $configurable;
        $this->_productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * Returns true if attribute exists and false if it doesn't exist
     *
     * @param string $field
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isProductAttributeExists($field)
    {
        $attr = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field);

        return ($attr && $attr->getId());
    }

    /**
     * Update attribute counters attributes for simple products
     *
     * @param \Magento\Catalog\Model\Product\Product $product
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function updateSimpleProductCounters($product, $attributeCode, $attributeCounterCode)
    {
        if (is_array($product->getData($attributeCode))) {
            $attributeBaseArray = $product->getData($attributeCode);
        } else {
            if (trim($product->getData($attributeCode)) != "") {
                $attributeBaseArray = explode(",", $product->getData($attributeCode));
            } else {
                $attributeBaseArray = [];
            }
        }

        $attribute = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        $options = $attribute->getSource()->getAllOptions();
        foreach ($options as $op) {
            $attributeCounterOptionCode = $attributeCounterCode;
            if ($op["value"]) {
                $attributeCounterOptionCode .= "_" . $op["value"];
            }
            $attributeCounter = $product->getData($attributeCounterOptionCode);
            $newAC = "";
            if (count($attributeBaseArray) == 0 || !in_array($op["value"], $attributeBaseArray)) {
                $newAC = "999";
            } else {
                $newAC = str_pad(count($attributeBaseArray), 3, '0', STR_PAD_LEFT);
            }
            if ($attributeCounter != $newAC) {
                $product->setData($attributeCounterOptionCode, $newAC);
            }
        }
    }

    /**
     * Update attribute counters attributes for ṕarent products
     *
     * @param \Magento\Catalog\Model\Product\Product $product
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function updateParentProductCounters($product, $attributeCode, $attributeCounterCode, $forceSave = false)
    {
        if ($product->getTypeInstance() instanceof \Magento\Catalog\Model\Product\Type\Simple) {
            return  $this;
        }

        $children = $product->getTypeInstance()->getUsedProducts($product);

        $attribute = $this->_eavConfig->getAttribute('catalog_product', $attributeCode);
        $options = $attribute->getSource()->getAllOptions();
        foreach ($options as $op) {
            $attributeCounterOptionCode = $attributeCounterCode;
            if ($op["value"]) {
                $attributeCounterOptionCode .= "_" . $op["value"];
            }
            $counters = [];
            foreach ($children as $child) {
                $acValues = $child->getData($attributeCounterOptionCode);
                if ($acValues) {
                    $counters[] = $acValues;
                } else {
                    $counters[] = "999";
                }
            }
            if (count($counters)) {
                $product->setData($attributeCounterOptionCode, min($counters));
            }
        }
        if ($forceSave) {
            $this->_productRepository->save($product);
        }
    }

    /**
     * Updating counters attributes data.
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        try {
            $product = $observer->getEvent()->getProduct();
            if ($product->getStoreId() != 0) {
                return $this;
            }
            if (!$this->isProductAttributeExists("holiday") && !$this->isProductAttributeExists("season")) {
                return $this;
            }

            if ($product->getTypeId() == "simple") {
                $this->updateSimpleProductCounters($product, "holiday", "holidays_counter");
                $this->updateSimpleProductCounters($product, "season", "seasons_counter");
                if ($observer->getEvent()->getName() != "catalog_product_save_after") {
                    return $this;
                }
                $parentProductIds = [];
                if ($product->getId()) {
                    $parentProductIds = $this->_configurable->getParentIdsByChild($product->getId());
                }
                if(isset($parentProductIds[0])){
                    $parentProduct = $this->_productRepository->getById($parentProductIds[0]);
                    $this->updateParentProductCounters($parentProduct, "holiday", "holidays_counter", true);
                    $this->updateParentProductCounters($parentProduct, "season", "seasons_counter", true);
                }
            } else {
                $this->updateParentProductCounters($product, "holiday", "holidays_counter");
                $this->updateParentProductCounters($product, "season", "seasons_counter");
            }
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return $this;
    }
}
