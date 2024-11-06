<?php

namespace Uzer\Customer\Observer;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Uzer\Core\Logger\Logger;
use Uzer\Customer\Api\Data\InformationBusinessInterface;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;

class PaymentMethodAvailable implements ObserverInterface
{

    protected Session $session;
    protected CollectionFactory $collectionFactory;

    /**
     * @param Session $session
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Session $session, CollectionFactory $collectionFactory)
    {
        $this->session = $session;
        $this->collectionFactory = $collectionFactory;
    }


    public function execute(Observer $observer)
    {
        $logger = ObjectManager::getInstance()->create(Logger::class);
        $logger->info('Observer');
        $methodInstance = $observer->getEvent()->getMethodInstance();
        $logger->info('Method: ' . $methodInstance->getCode());
        if ($methodInstance->getCode() == 'terms') {
            try {
                $logger->info('Validating payment method');
                $customerId = $this->session->getCustomerId();
                /** @var InformationBusiness $businessInformation */
                $businessInformation = $this->collectionFactory->create()->addFieldToFilter(InformationBusinessInterface::CUSTOMERS_ID, array('eq' => $customerId))->load()->getFirstItem();
                $allowCreditTerms = $businessInformation->hasData() && $businessInformation->getPaymentTerms();
                $result = $observer->getEvent()->getResult();
                $result->setData('is_available', $allowCreditTerms);
            } catch (\Exception $ex) {
            }
        }
    }
}
