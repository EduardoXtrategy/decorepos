<?php

namespace Uzer\Infor\Model\Data;

use Magento\Framework\DataObject;
use Uzer\Infor\Api\Data\ModelItemInterface;
use Uzer\Infor\Api\Data\RequestModelInterface;

class RequestModel extends DataObject implements RequestModelInterface
{
    /**
     * Getter for Action.
     *
     * @return int|null
     */
    public function getAction(): ?int
    {
        return $this->getData(self::ACTION) === null ? null
            : (int)$this->getData(self::ACTION);
    }

    /**
     * Setter for Action.
     *
     * @param int|null $action
     *
     * @return void
     */
    public function setAction(?int $action): void
    {
        $this->setData(self::ACTION, $action);
    }

    /**
     * Getter for ItemId.
     *
     * @return string|null
     */
    public function getItemId(): ?string
    {
        return $this->getData(self::ITEM_ID) === null ? null
            : (string)$this->getData(self::ITEM_ID);
    }

    /**
     *
     * Setter for ItemId.
     *
     * @param string|null $itemId
     * @return void
     */
    public function setItemId(?string $itemId): void
    {
        $this->setData(self::ITEM_ID, $itemId);
    }

    /**
     * Getter for Properties.
     *
     * @return \Uzer\Infor\Api\Data\ModelItemInterface[]|null
     */
    public function getProperties(): ?array
    {
        return $this->getData(self::PROPERTIES) === null ? null
            : (array)$this->getData(self::PROPERTIES);
    }

    /**
     * Setter for Properties.
     *
     * @param \Uzer\Infor\Api\Data\ModelItemInterface[]|null $properties
     *
     * @return void
     */
    public function setProperties(?array $properties): void
    {
        $this->setData(self::PROPERTIES, $properties);
    }

    /**
     * Add a property to the list of properties
     *
     * @param ModelItemInterface $property
     * @return void
     */
    public function appendProperty(ModelItemInterface $property): void
    {
        $properties = $this->getProperties();
        $properties[] = $property;
        $this->setProperties($properties);
    }


    /**
     * Convert the object to an array
     *
     * @return array
     */
    public function toArray(array $keys = []): array
    {
        $data = $this->getData();
        $properties = $this->getProperties();
        if ($properties) {
            $data[self::PROPERTIES] = [];
            foreach ($properties as $property) {
                $data[self::PROPERTIES][] = $property->toArray();
            }
        }
        return $data;
    }
}
