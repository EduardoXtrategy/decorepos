<?php

namespace Uzer\AdvancedFilter\ViewModel\Catalog;

use Amasty\Shopby\Model\Layer\Filter\Item;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class GroupFilter extends DataObject implements ArgumentInterface
{

    private string $code;
    /** @var \Amasty\Shopby\Model\Layer\Filter\Item[] */
    private array $items = [];
    private string $label;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return \Amasty\Shopby\Model\Layer\Filter\Item[]
     */
    public function getItems(): array
    {
        ksort($this->items);
        return $this->items;
    }

    /**
     * @param \Amasty\Shopby\Model\Layer\Filter\Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }


    public function addItem(Item $sizeFilter, int $position = null)
    {
        if (!is_null($position)) {
            $this->items[$position] = $sizeFilter;
        } else {
            $this->items[] = $sizeFilter;
        }
    }


}
