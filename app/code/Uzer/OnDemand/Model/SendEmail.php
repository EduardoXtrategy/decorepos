<?php

namespace Uzer\OnDemand\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Phrase;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Uzer\OnDemand\Helper\Data;

class SendEmail
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
    public function __construct(
        Data                  $data,
        TransportBuilder      $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface        $inlineTranslation,
        ScopeConfigInterface  $scopeConfig
    )
    {
        $this->data = $data;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
    }


    public function sendEmail(OnDemandRequest $onDemandRequest)
    {
        $email = $this->scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sender = [
            'name' => 'Decowraps',
            'email' => $email,
        ];
        try {
            $templateId = $this->getTemplate($onDemandRequest);
            $this->inlineTranslation->suspend();
            $transportBuilder = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($this->getTemplateOptions())
                ->setTemplateVars($this->getTemplateVars($onDemandRequest))
                ->setFromByScope($sender);
            foreach ($this->getEmails($onDemandRequest) as $email) {
                $transportBuilder = $transportBuilder->addTo($email);
            }
            $transport = $transportBuilder->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            ObjectManager::getInstance()->create(LoggerInterface::class)->info('Exception send email bModel: ' . $e->getMessage());
        }
    }

    public function getEmails(OnDemandRequest $onDemandRequest)
    {
        $preSale = $this->data->getPreSaleAttribute($this->storeManager->getStore()->getId());
        if ($onDemandRequest->getBModelId() == $preSale) {
            return $this->data->getEmailsPresale($this->storeManager->getStore()->getId());
        }
        return $this->data->getEmails($this->storeManager->getStore()->getId());
    }

    public function getTemplate(OnDemandRequest $onDemandRequest)
    {
        $preSale = $this->data->getPreSaleAttribute($this->storeManager->getStore()->getId());
        if ($onDemandRequest->getBModelId() == $preSale) {
            $templateId = $this->data->getEmailTemplatePresaleId($this->storeManager->getStore()->getId());
        } else {
            $templateId = $this->data->getEmailTemplateId($this->storeManager->getStore()->getId());
        }
        return $templateId;
    }


    /**
     * @return array
     * @throws NoSuchEntityException
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
     * @throws NoSuchEntityException
     */
    public function getTemplateVars(OnDemandRequest $onDemandRequest): array
    {
        $data = $onDemandRequest->getData();
        $data['subject'] = $this->getSubjectEmail($onDemandRequest);
        return $data;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getSubjectEmail(OnDemandRequest $onDemandRequest): string
    {
        return $onDemandRequest->getProductName();
    }


}
