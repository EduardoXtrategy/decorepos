<?php

namespace Uzer\Customer\Helper;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Customer\Api\Data\InformationBusinessInterface;
use Uzer\Customer\Model\CustomerCustomInfo;

class SendEmail
{
    protected Data $data;
    protected TransportBuilder $transportBuilder;
    protected StoreManagerInterface $storeManager;
    protected StateInterface $inlineTranslation;
    protected ScopeConfigInterface $scopeConfig;
    protected CustomerCustomInfo $customerCustomInfo;

    /**
     * @param Data $data
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param CustomerCustomInfo $customerCustomInfo
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Data                  $data,
        TransportBuilder      $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface        $inlineTranslation,
        CustomerCustomInfo    $customerCustomInfo,
        ScopeConfigInterface  $scopeConfig)
    {
        $this->data = $data;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->customerCustomInfo = $customerCustomInfo;
    }


    public function sendEmail(InformationBusinessInterface $informationBusiness, CustomerInterface $customer)
    {
        $email = $this->scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sender = [
            'name' => 'Decowraps',
            'email' => $email,
        ];
        try {
            $templateId = $this->data->emailTemplateId($this->storeManager->getStore()->getId());
            $this->inlineTranslation->suspend();
            $transportBuilder = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($this->getTemplateOptionsByWebsite($customer->getWebsiteId()))
                ->setTemplateVars($this->getTemplateVars($informationBusiness))
                ->setFromByScope($sender);
            $transportBuilder->addTo($customer->getEmail());
            $transportBuilder->addCc('digitaldecowraps@gmail.com');
            $transport = $transportBuilder->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getTemplateOptions(): array
    {
        $storeId = $this->storeManager->getStore()->getId();
        return array(
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId,
        );
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws LocalizedException
     */
    private function getTemplateOptionsByWebsite($websiteId): array
    {
        $storeId = $this->storeManager->getStore()->getId();
        return array(
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $this->storeManager->getWebsite($websiteId)->getDefaultStore()->getId(),
        );
    }

    public function getTemplateVars(InformationBusinessInterface $informationBusiness): array
    {
        $data = $informationBusiness->getData();
        $data['company'] = $this->customerCustomInfo->getByCustomerId($informationBusiness->getCustomersId(), 'company_data');
        return $data;
    }
}
