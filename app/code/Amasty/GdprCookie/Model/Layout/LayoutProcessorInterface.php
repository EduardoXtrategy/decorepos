<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Cookie Consent (GDPR) for Magento 2
*/
declare(strict_types=1);

namespace Amasty\GdprCookie\Model\Layout;

interface LayoutProcessorInterface
{
    public function process(array $jsLayout): array;
}
