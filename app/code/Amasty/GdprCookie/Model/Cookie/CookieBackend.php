<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Cookie Consent (GDPR) for Magento 2
*/

declare(strict_types=1);

namespace Amasty\GdprCookie\Model\Cookie;

class CookieBackend extends CookieManagement
{
    protected function createCookieCollection(int $storeId = 0)
    {
        $collection = $this->cookieCollectionFactory->create();
        $collection->setStoreId($storeId);

        return $collection;
    }
}
