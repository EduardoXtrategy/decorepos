<?php

namespace Uzer\Theme\Model;

use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Cookie
{

    const COOKIE_KEY = 'decowraps_store_selected';
    private CookieManagerInterface $cookieManager;
    private CookieMetadataFactory $cookieMetadataFactory;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory  $cookieMetadataFactory
    )
    {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function setCustomCookie($storeId): PublicCookieMetadata
    {
        $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $publicCookieMetadata->setDurationOneYear();
        $publicCookieMetadata->setPath('/');
        $publicCookieMetadata->setHttpOnly(true);
        $this->cookieManager->setPublicCookie(self::COOKIE_KEY, $storeId, $publicCookieMetadata);
        return $publicCookieMetadata;
    }


    /**
     * @return string|null
     */
    public function getCustomCookie(): ?string
    {
        return $this->cookieManager->getCookie(self::COOKIE_KEY);
    }
}
