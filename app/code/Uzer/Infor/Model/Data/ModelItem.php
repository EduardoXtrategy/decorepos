<?php

namespace Uzer\Infor\Model\Data;

use Magento\Framework\DataObject;
use Uzer\Infor\Api\Data\ModelItemInterface;

class ModelItem extends DataObject implements ModelItemInterface
{
    /**
     * Getter for IsNull.
     *
     * @return bool|null
     */
    public function getIsNull(): ?bool
    {
        return $this->getData(self::IS_NULL) === null ? null
            : (bool)$this->getData(self::IS_NULL);
    }

    /**
     * Setter for IsNull.
     *
     * @param bool|null $isNull
     *
     * @return void
     */
    public function setIsNull(?bool $isNull): void
    {
        $this->setData(self::IS_NULL, $isNull);
    }

    /**
     * Getter for Modified.
     *
     * @return bool|null
     */
    public function getModified(): ?bool
    {
        return $this->getData(self::MODIFIED) === null ? null
            : (bool)$this->getData(self::MODIFIED);
    }

    /**
     * Setter for Modified.
     *
     * @param bool|null $modified
     *
     * @return void
     */
    public function setModified(?bool $modified): void
    {
        $this->setData(self::MODIFIED, $modified);
    }

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * Getter for Value.
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->getData(self::VALUE);
    }

    /**
     * Setter for Value.
     *
     * @param string|null $value
     *
     * @return void
     */
    public function setValue(?string $value): void
    {
        $this->setData(self::VALUE, $value);
    }
}
