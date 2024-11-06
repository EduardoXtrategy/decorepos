<?php

declare(strict_types=1);

namespace Sensei\SortingPro\Block;

use Sensei\SortingPro\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Amasty\Base\Model\Serializer as JsonSerializer;

class Direction extends Template
{
    const PERMANENT_DIRECTION_ATTRIBUTES = [
        'price_asc',
        'price_desc'
    ];

    /**
     * @var string
     */
    protected $_template = 'Sensei_SortingPro::direction.phtml';

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    public function __construct(
        Data $helper,
        JsonSerializer $jsonSerializer,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $result = '';

        if ($this->isDirectionCanBeHide()) {
            $result = parent::_toHtml();
        }

        return $result;
    }

    /**
     * @return bool
     */
    private function isDirectionCanBeHide(): bool
    {
        $result = false;
        foreach ($this->getPermanentDirectionAttributes() as $attribute) {
            if ($result = !$this->helper->isMethodDisabled($attribute)) {
                break;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getPermanentDirectionAttributes(): array
    {
        return static::PERMANENT_DIRECTION_ATTRIBUTES;
    }

    /**
     * @return string
     */
    public function getPermanentDirectionAttributesJson(): string
    {
        return $this->jsonSerializer->serialize($this->getPermanentDirectionAttributes());
    }
}
