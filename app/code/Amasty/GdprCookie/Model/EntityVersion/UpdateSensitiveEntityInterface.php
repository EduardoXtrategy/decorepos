<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Cookie Consent (GDPR) for Magento 2
*/

namespace Amasty\GdprCookie\Model\EntityVersion;

interface UpdateSensitiveEntityInterface
{
    /**
     * @return array
     */
    public function getSensitiveData(): array;
}
