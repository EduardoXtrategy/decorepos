<?php

namespace Uzer\Customer\Plugin\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Payment\Api\Data\PaymentMethodInterface;
use Magento\Payment\Model\MethodList;
use Uzer\Core\Logger\Logger;
use Uzer\Customer\Api\Data\InformationBusinessInterface;
use Uzer\Customer\Model\InformationBusiness;
use Uzer\Customer\Model\ResourceModel\InformationBusiness\CollectionFactory;

class MethodListWrapper
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


    /**
     * @param MethodList $subject
     * @param PaymentMethodInterface[] $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAvailableMethods(MethodList $subject, array $result): array
    {
        $logger = ObjectManager::getInstance()->create(Logger::class);
        $logger->info('Enter here');
        $customerId = $this->session->getCustomerId();
        /** @var InformationBusiness $businessInformation */
        $businessInformation = $this->collectionFactory->create()->addFieldToFilter(InformationBusinessInterface::CUSTOMERS_ID, array('eq' => $customerId))->load()->getFirstItem();
        $allowCreditTerms = $businessInformation->hasData() && $businessInformation->getPaymentTerms();
        foreach ($result as $key => $_result) {
            if ($_result->getCode() == 'terms' && !$allowCreditTerms) {
                unset($result[$key]);
            }
        }
        return $result;
    }
}
