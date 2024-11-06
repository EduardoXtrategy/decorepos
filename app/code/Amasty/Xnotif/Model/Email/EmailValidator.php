<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Out of Stock Notification for Magento 2
 */

namespace Amasty\Xnotif\Model\Email;

use Magento\Framework\Exception\LocalizedException;

class EmailValidator
{
    /**
     * @param string $email
     *
     * @return string
     * @throws LocalizedException
     */
    public function execute(string $email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (!\Zend_Validate::is($email, 'EmailAddress')) {
            throw new LocalizedException(__('Please enter a valid email address.'));
        }

        return $email;
    }
}
