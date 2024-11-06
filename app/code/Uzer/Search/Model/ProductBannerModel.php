<?php

namespace Uzer\Search\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Search\Api\Data\ProductBannerInterface;
use Uzer\Search\Model\ResourceModel\ProductBannerResource;

class ProductBannerModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'product_banner_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ProductBannerResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeId(): ?int
    {
        return $this->getData(ProductBannerInterface::ATTRIBUTE_ID) === null ? null
            : (int)$this->getData(ProductBannerInterface::ATTRIBUTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setAttributeId(?int $attributeId): void
    {
        $this->setData(ProductBannerInterface::ATTRIBUTE_ID, $attributeId);
    }

    /**
     * @inheritDoc
     */
    public function getContent(): ?string
    {
        return $this->getData(ProductBannerInterface::CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function setContent(?string $content): void
    {
        $this->setData(ProductBannerInterface::CONTENT, $content);
    }

    /**
     * @inheritDoc
     */
    public function getStoreId()
    {
        return $this->getData(ProductBannerInterface::STORE_ID) === null ? null
            : $this->getData(ProductBannerInterface::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(?int $storeId): void
    {
        $this->setData(ProductBannerInterface::STORE_ID, $storeId);
    }

    public function getAttributeName(): ?string
    {
        return $this->getData(ProductBannerInterface::ATTRIBUTE_NAME);
    }

    public function setAttributeName(?string $attributeName): void
    {
        $this->setData(ProductBannerInterface::ATTRIBUTE_NAME, $attributeName);
    }
}
