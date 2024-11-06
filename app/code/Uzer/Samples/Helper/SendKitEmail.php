<?php

namespace Uzer\Samples\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Samples\Model\SampleKit;

class SendKitEmail
{

    protected Data $data;
    protected TransportBuilder $transportBuilder;
    protected StoreManagerInterface $storeManager;
    protected StateInterface $inlineTranslation;
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param Data $data
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Data $data, TransportBuilder $transportBuilder, StoreManagerInterface $storeManager, StateInterface $inlineTranslation, ScopeConfigInterface $scopeConfig)
    {
        $this->data = $data;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
    }


    public function sendEmail(SampleKit $sampleKit)
    {
        $email = $this->scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sender = [
            'name' => 'Decowraps',
            'email' => $email,
        ];
        try {
            $templateId = $this->data->kitEmailTemplateId($this->storeManager->getStore()->getId());
            $this->inlineTranslation->suspend();
            $transportBuilder = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($this->getTemplateOptions())
                ->setTemplateVars($this->getTemplateVars($sampleKit))
                ->setFromByScope($sender);
            foreach ($this->data->getEmails($this->storeManager->getStore()->getId()) as $email) {
                $transportBuilder = $transportBuilder->addTo($email);
            }
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

    public function getTemplateVars(SampleKit $sampleKit): array
    {
        return $sampleKit->getData();
    }


}
