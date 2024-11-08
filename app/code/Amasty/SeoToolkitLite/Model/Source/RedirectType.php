<?php

declare(strict_types=1);

namespace Amasty\SeoToolkitLite\Model\Source;

class RedirectType implements \Magento\Framework\Data\OptionSourceInterface
{
    const REDIRECT_301 = 301;
    const REDIRECT_302 = 302;

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::REDIRECT_301, 'label' => __('301 Moved Permanently')],
            ['value' => self::REDIRECT_302, 'label' => __('302 Found')]
        ];
    }
}
