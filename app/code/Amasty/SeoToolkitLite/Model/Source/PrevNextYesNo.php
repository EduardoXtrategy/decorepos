<?php

declare(strict_types=1);

namespace Amasty\SeoToolkitLite\Model\Source;

class PrevNextYesNo implements \Magento\Framework\Data\OptionSourceInterface
{
    const NO = 0;
    const YES = 1;

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::NO, 'label' => __('No')],
            ['value' => self::YES, 'label' => __('Yes (Deprecated)')]
        ];
    }
}
