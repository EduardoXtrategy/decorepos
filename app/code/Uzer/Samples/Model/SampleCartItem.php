<?php

namespace Uzer\Samples\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\AbstractModel;
use Uzer\Samples\Api\Data\SampleCartItemInterface;
use Uzer\Samples\Model\ResourceModel\SampleCartItem as ResourceModel;
use Uzer\Samples\Model\ResourceModel\SampleCartItem\Collection;

class SampleCartItem extends AbstractModel implements SampleCartItemInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'sample_cart_items_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getSamplesCartId(): ?int
    {
        return $this->getData(self::SAMPLES_CART_ID) === null ? null
            : (int)$this->getData(self::SAMPLES_CART_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSamplesCartId(?int $samplesCartId): void
    {
        $this->setData(self::SAMPLES_CART_ID, $samplesCartId);
    }

    /**
     * @inheritDoc
     */
    public function getSku(): ?string
    {
        return $this->getData(self::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku(?string $sku): void
    {
        $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getIsParent(): ?bool
    {
        return $this->getData(self::IS_PARENT) === null ? null
            : (bool)$this->getData(self::IS_PARENT);
    }

    /**
     * @inheritDoc
     */
    public function setIsParent(?bool $isParent): void
    {
        $this->setData(self::IS_PARENT, $isParent);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return $this->getData(self::PARENT);
    }

    /**
     * @inheritDoc
     */
    public function setParent(?string $parent): void
    {
        $this->setData(self::PARENT, $parent);
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): ?string
    {
        return $this->getData(self::ATTRIBUTES);
    }

    /**
     * @inheritDoc
     */
    public function setAttributes(?string $attributes): void
    {
        $this->setData(self::ATTRIBUTES, $attributes);
    }

    /**
     * @inheritDoc
     */
    public function getQty(): ?int
    {
        return $this->getData(self::QTY) === null ? null
            : (int)$this->getData(self::QTY);
    }

    /**
     * @inheritDoc
     */
    public function setQty(?int $qty): void
    {
        $this->setData(self::QTY, $qty);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): ?int
    {
        return $this->getData(self::PRODUCT_ID) === null ? null
            : (int)$this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId(?int $productId): void
    {
        $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @return SampleCartItem[]
     */
    public function getChildren(): array
    {
        $collection = ObjectManager::getInstance()->create(Collection::class);
        return $collection->addFieldToFilter('is_parent', array('eq' => 0))
            ->addFieldToFilter('parent', array('eq' => $this->getSku()))
            ->addFieldToFilter('samples_cart_id', array('eq' => $this->getSamplesCartId()))
            ->load()
            ->getItems();
    }
}
