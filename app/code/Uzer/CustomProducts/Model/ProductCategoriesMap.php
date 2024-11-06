<?php

namespace Uzer\CustomProducts\Model;

class ProductCategoriesMap
{

    private string $sku;
    private int $position = 0;
    private array $categories = [];

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return ProductCategoriesMap
     */
    public function setSku(string $sku): ProductCategoriesMap
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }


    /**
     * @param $id
     * @return $this
     */
    public function addCategory($id): ProductCategoriesMap
    {
        if (!in_array($id, $this->categories)) {
            $this->categories[] = $id;
        }
        return $this;
    }

    public function addCategories(array $ids): ProductCategoriesMap
    {
        $this->categories = array_merge($this->categories, $ids);
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return ProductCategoriesMap
     */
    public function setPosition(int $position): ProductCategoriesMap
    {
        $this->position = $position;
        return $this;
    }
}
