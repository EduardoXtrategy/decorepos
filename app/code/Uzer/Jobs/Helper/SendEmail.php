<?php

namespace Uzer\Jobs\Helper;

use Laminas\Mime\Part;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\TransportInterface;
use Magento\Store\Model\StoreManagerInterface;
use Uzer\Jobs\Model\RequestJob;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Uzer\Jobs\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Zend_Mime;
use Magento\Framework\Filesystem\Io\File;

class SendEmail extends AbstractHelper
{
    protected StateInterface $inlineTranslation;
    protected Escaper $escaper;
    protected TransportBuilder $transportBuilder;
    private StoreManagerInterface $storeManager;
    private File $file;

    public function __construct(
        StateInterface        $inlineTranslation,
        Escaper               $escaper,
        StoreManagerInterface $storeManager,
        TransportBuilder      $transportBuilder,
        File                  $file,
        Context               $context
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->file = $file;
    }


    public function sendEmail(RequestJob $requestJob, string $content = null, string $fileName = null, string $fileType = null)
    {
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
            $emailFrom = $scopeConfig->getValue('trans_email/ident_support/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $this->inlineTranslation->suspend();
            $sendTo = explode(',', $this->scopeConfig->getValue('jobs/configuration/emails', ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getId()));
            $sender = [
                'name' => 'Decowraps',
                'email' => $emailFrom,
            ];
            $templateId = $this->scopeConfig->getValue('jobs/configuration/template', ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getId());
            foreach ($sendTo as $recipient) {
                $transport = $this->transportBuilder
                    ->setTemplateIdentifier($templateId)
                    ->setTemplateOptions($this->getTemplateOptions())
                    ->setTemplateVars($this->getTemplateVars($requestJob))
                    ->addAttachment($content, $fileName, $fileType)
                    ->setFromByScope($sender)
                    ->addTo($recipient)
                    ->getTransport();
                $transport->sendMessage();
            }
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
        }
    }


    public function getTemplateOptions(): array
    {
        $storeId = $this->storeManager->getStore()->getId();
        return array(
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId,
        );
    }

    public function getTemplateVars(RequestJob $requestJob): array
    {
        return $requestJob->getData();
    }
}
