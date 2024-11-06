<?php

namespace Uzer\Catalogs\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Catalogs\Api\Data\CatalogInterface;
use Uzer\Catalogs\Model\ResourceModel\Catalog as ResourceModel;

class Catalog extends AbstractModel implements CatalogInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_catalogs_model';

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
    public function getImage(): ?string
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * @inheritDoc
     */
    public function setImage(?string $image): void
    {
        $this->setData(self::IMAGE, $image);
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
    public function getLink(): ?string
    {
        return $this->getData(self::LINK);
    }

    /**
     * @inheritDoc
     */
    public function setLink(?string $link): void
    {
        $this->setData(self::LINK, $link);
    }

    /**
     * @inheritDoc
     */
    public function getActive(): ?bool
    {
        return $this->getData(self::ACTIVE) === null ? null
            : (bool)$this->getData(self::ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setActive(?bool $active): void
    {
        $this->setData(self::ACTIVE, $active);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(?string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getImageName(): ?string
    {
        return $this->getData(self::IMAGE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setImageName(?string $imageName): void
    {
        $this->setData(self::IMAGE_NAME, $imageName);
    }

    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    public function setStoreId($storeId)
    {
        $this->setData(self::STORE_ID, $storeId);
    }
}
