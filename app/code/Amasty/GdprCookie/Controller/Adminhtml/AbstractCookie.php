<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Cookie Consent (GDPR) for Magento 2
*/

namespace Amasty\GdprCookie\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class AbstractCookie extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Amasty_GdprCookie::cookies';
}
