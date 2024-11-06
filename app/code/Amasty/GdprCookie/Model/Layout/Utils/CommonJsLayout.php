<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Cookie Consent (GDPR) for Magento 2
*/
declare(strict_types=1);

namespace Amasty\GdprCookie\Model\Layout\Utils;

use Amasty\GdprCookie\Model\ConfigProvider;
use Magento\Cms\Model\Template\Filter as CmsTemplateFilter;

class CommonJsLayout
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var CmsTemplateFilter
     */
    private $cmsTemplateFilter;

    /**
     * @var string
     */
    private $notificationText;

    /**
     * Storage of common js layout
     *
     * @var array
     */
    private $preparedJsLayout;

    public function __construct(
        ConfigProvider $configProvider,
        CmsTemplateFilter $cmsTemplateFilter
    ) {
        $this->configProvider = $configProvider;
        $this->cmsTemplateFilter = $cmsTemplateFilter;
    }

    public function get(): array
    {
        if (!$this->preparedJsLayout) {
            $this->preparedJsLayout = [
                'isCookieBarEnabled' => $this->configProvider->isCookieBarEnabled(),
                'config' => [
                    'isDeclineEnabled' => $this->configProvider->getDeclineEnabled(),
                    'barLocation' => (int)$this->configProvider->getBarLocation(),
                    'policyText' => $this->getNotificationText(),
                    'firstShowProcess' => (string)$this->configProvider->getFirstVisitShow(),
                    'cssConfig' => $this->getCssConfig()
                ],
                'jsComponents' => [
                    'components' => [
                        'gdpr-cookie-modal' => [
                            'cookieText' => $this->getNotificationText(),
                            'firstShowProcess' => (string)$this->configProvider->getFirstVisitShow(),
                            'acceptBtnText' => $this->configProvider->getAcceptButtonName(),
                            'declineBtnText' => $this->configProvider->getDeclineButtonName(),
                            'settingsBtnText' => $this->configProvider->getSettingsButtonName(),
                            'isDeclineEnabled' => $this->configProvider->getDeclineEnabled()
                        ]
                    ]
                ]
            ];
        }

        return $this->preparedJsLayout;
    }

    private function getNotificationText(): string
    {
        if ($this->notificationText === null) {
            $this->notificationText = $this->cmsTemplateFilter->filter($this->configProvider->getNotificationText());
        }

        return $this->notificationText;
    }

    private function getCssConfig(): array
    {
        return [
            'backgroundColor' => $this->configProvider->getBackgroundColor(),
            'policyTextColor' => $this->configProvider->getPolicyTextColor(),
            'textColor' => $this->configProvider->getDescriptionTextColor(),
            'titleColor' => $this->configProvider->getTitleTextColor(),
            'linksColor' => $this->configProvider->getLinksColor(),
            'acceptBtnColor' => $this->configProvider->getAcceptButtonColor(),
            'acceptBtnColorHover' => $this->configProvider->getAcceptButtonColorHover(),
            'acceptBtnTextColor' => $this->configProvider->getAcceptTextColor(),
            'acceptBtnTextColorHover' => $this->configProvider->getAcceptTextColorHover(),
            'acceptBtnOrder' => $this->configProvider->getAcceptButtonOrder(),
            'allowBtnTextColor' => $this->configProvider->getSettingsTextColor(),
            'allowBtnTextColorHover' => $this->configProvider->getSettingsTextColorHover(),
            'allowBtnColor' => $this->configProvider->getSettingsButtonColor(),
            'allowBtnColorHover' => $this->configProvider->getSettingsButtonColorHover(),
            'allowBtnOrder' => $this->configProvider->getSettingsButtonOrder(),
            'declineBtnTextColorHover' => $this->configProvider->getDeclineTextColorHover(),
            'declineBtnColorHover' => $this->configProvider->getDeclineButtonColorHover(),
            'declineBtnTextColor' => $this->configProvider->getDeclineTextColor(),
            'declineBtnColor' => $this->configProvider->getDeclineButtonColor(),
            'declineBtnOrder' => $this->configProvider->getDeclineButtonOrder(),
        ];
    }
}
