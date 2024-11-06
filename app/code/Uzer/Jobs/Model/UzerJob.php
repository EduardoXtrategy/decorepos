<?php

namespace Uzer\Jobs\Model;

use Magento\Framework\Model\AbstractModel;
use Uzer\Jobs\Api\Data\UzerJobInterface;

class UzerJob extends AbstractModel implements UzerJobInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'uzer_jobs_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(\Uzer\Jobs\Model\ResourceModel\UzerJob::class);
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
    public function getLocation(): ?string
    {
        return $this->getData(self::LOCATION);
    }

    /**
     * @inheritDoc
     */
    public function setLocation(?string $location): void
    {
        $this->setData(self::LOCATION, $location);
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
    public function getStatus(): ?bool
    {
        return $this->getData(self::STATUS) === null ? null
            : (bool)$this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(?bool $status): void
    {
        $this->setData(self::STATUS, $status);
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
